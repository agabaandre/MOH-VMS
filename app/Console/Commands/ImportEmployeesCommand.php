<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Modules\Employee\Entities\Department;
use Modules\Employee\Entities\Driver;
use Modules\Employee\Entities\Employee;
use Modules\Employee\Entities\LicenseType;
use Modules\Employee\Entities\Position;
use Modules\VehicleManagement\Entities\Facility;

class ImportEmployeesCommand extends Command
{
    protected $signature = 'import:employees {--batch-size=100 : Number of records to process in each batch}';
    protected $description = 'Import employees from HRIS API';

    protected function getDataFromApi(): array
    {
        $response = Http::timeout(60)->get('https://hris.health.go.ug/apiv1/index.php/api/ihrisdata');

        if (!$response->successful()) {
            throw new \Exception("Failed to fetch data from HRIS API: " . $response->status());
        }

        $data = $response->json();

        if (!is_array($data)) {
            throw new \Exception('Invalid response format from HRIS API');
        }

        return $data;
    }

    protected function deleteInvalidRecords(): int
    {
        $this->info("Deleting invalid records...");
        
        // Get facility ID for Ministry of Health
        $mohFacility = Facility::where('name', 'Ministry of Health')->first();
        if (!$mohFacility) {
            throw new \Exception('Ministry of Health facility not found in database');
        }

        $query = Employee::query()
            ->where(function($q) use ($mohFacility) {
                $q->whereNull('nid')
                  ->orWhere('nid', '')
                  ->orWhereNull('facility_id')
                  ->orWhere('facility_id', '!=', $mohFacility->id);
            });

        $count = $query->count();
        
        if ($count > 0) {
            // Delete associated drivers first
            Driver::whereIn('employee_id', $query->pluck('id'))->delete();
            
            // Then delete employees
            $query->delete();
            
            $this->info("Deleted {$count} invalid records");
            Log::info("Deleted invalid employee records", [
                'count' => $count,
                'reasons' => [
                    'null_nin' => true,
                    'non_moh_facility' => true,
                    'null_facility' => true
                ]
            ]);
        } else {
            $this->info("No invalid records found");
        }

        return $count;
    }

    public function handle()
    {
        try {
            // Delete invalid records first
            $deletedCount = $this->deleteInvalidRecords();

            $startTime = microtime(true);
            $batchSize = (int) $this->option('batch-size');

            Log::info('Starting employee import process', [
                'batch_size' => $batchSize,
                'records_deleted' => $deletedCount
            ]);

            $this->info("Fetching data from HRIS API...");
            $data = $this->getDataFromApi();
            $totalEntries = count($data);
            $batches = array_chunk($data, $batchSize);
            
            $successCount = 0;
            $errorCount = 0;
            $skippedCount = 0;
            $skippedReasons = [
                'missing_name' => 0,
                'missing_nin' => 0,
                'non_moh' => 0
            ];

            $this->info("Starting import of {$totalEntries} employees in " . count($batches) . " batches...");
            $this->output->progressStart($totalEntries);

            foreach ($batches as $batchIndex => $batchData) {
                $this->info("\nProcessing batch " . ($batchIndex + 1) . " of " . count($batches));
                \DB::beginTransaction();

                try {
                    foreach ($batchData as $entry) {
                        // Skip if missing name
                        if (empty($entry['surname']) || empty($entry['firstname'])) {
                            Log::warning('Skipped entry: Missing surname or firstname', $entry);
                            $skippedCount++;
                            $skippedReasons['missing_name']++;
                            $this->output->progressAdvance();
                            continue;
                        }

                        // Skip if missing NIN
                        if (empty($entry['nin'])) {
                            Log::warning('Skipped entry: Missing National ID', $entry);
                            $skippedCount++;
                            $skippedReasons['missing_nin']++;
                            $this->output->progressAdvance();
                            continue;
                        }

                        // Process facility first to check if MOH
                        $facility = $this->processFacility($entry);
                        if (!$facility) {
                            $skippedCount++;
                            $skippedReasons['non_moh']++;
                            $this->output->progressAdvance();
                            continue;
                        }

                        $department = Department::firstOrCreate(['name' => $entry['department'] ?? 'Unassigned']);
                        $position = Position::firstOrCreate(['name' => $entry['job'] ?? 'Unspecified']);

                        $fullName = trim(sprintf('%s %s %s',
                            $entry['surname'],
                            $entry['firstname'],
                            $entry['othername'] ?? ''
                        ));

                        $email = $entry['email'] ?? $this->generateEmailFromName($fullName);
                        $dob = $this->validateAndParseDate($entry['birth_date']);

                        $employee = Employee::updateOrCreate(
                            ['employee_code' => $entry['ipps']],
                            [
                                'name' => $fullName,
                                'department_id' => $department->id,
                                'position_id' => $position->id,
                                'phone' => $entry['mobile'] ?? null,
                                'email' => $email,
                                'dob' => $dob,
                                'nid' => $entry['nin'] ?? null,
                                'facility_id' => $facility ? $facility->id : null,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        );

                        if (strtolower($position->name) === 'car driver') {
                            $this->createDriver($employee, $entry);
                        }

                        $successCount++;
                        $this->output->progressAdvance();
                    }

                    \DB::commit();
                    $this->info("Batch " . ($batchIndex + 1) . " completed successfully");

                } catch (\Exception $e) {
                    \DB::rollBack();
                    $errorCount += count($batchData);
                    $this->error("Batch " . ($batchIndex + 1) . " failed: " . $e->getMessage());
                    Log::error('Batch import failed', [
                        'batch_index' => $batchIndex,
                        'error' => $e->getMessage(),
                        'batch_data' => $batchData
                    ]);
                }
            }
            $this->output->progressFinish();
            $duration = round(microtime(true) - $startTime, 2);

            $this->newLine();
            $this->info("Import completed in {$duration} seconds");
            $this->table(
                ['Status', 'Count'],
                [
                    ['Successful', $successCount],
                    ['Failed', $errorCount],
                    ['Skipped (Missing Name)', $skippedReasons['missing_name']],
                    ['Skipped (Missing NIN)', $skippedReasons['missing_nin']],
                    ['Skipped (Non-MOH)', $skippedReasons['non_moh']],
                    ['Total Processed', $successCount + $errorCount + $skippedCount],
                ]
            );

            Log::info('Employee import completed', [
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'skipped_count' => $skippedCount,
                'skipped_reasons' => $skippedReasons,
                'total_processed' => $successCount + $errorCount + $skippedCount,
                'duration_seconds' => $duration,
                'batch_size' => $batchSize,
                'total_batches' => count($batches)
            ]);

            return 0;

        } catch (\Exception $e) {
            $this->error("\nImport failed: " . $e->getMessage());
            Log::error('Employee import failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'skipped_count' => $skippedCount
            ]);
            return 1;
        }
    }

    protected function validateAndParseDate(?string $dateString): ?string
    {
        if (empty($dateString) || $dateString === '0000-00-00') {
            return null;
        }

        try {
            $date = Carbon::parse($dateString);

            // Check if date is within reasonable range
            if ($date->year < 1900 || $date->isFuture()) {
                Log::warning('Date out of reasonable range', ['date' => $dateString]);
                return null;
            }

            return $date->toDateString();
        } catch (\Exception $e) {
            Log::warning('Failed to parse date', [
                'date' => $dateString,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Generate a unique email address from a name
     */
    protected function generateEmailFromName(string $name): string
    {
        // Convert to lowercase and replace spaces with dots
        $email = strtolower(str_replace(' ', '.', $name));
        
        // Remove special characters and dots at start/end
        $email = preg_replace('/[^a-z0-9.]/', '', $email);
        $email = trim($email, '.');
        
        // Ensure we have at least one character and add random string for uniqueness
        if (empty($email)) {
            $email = 'employee.' . Str::random(8);
        }
        
        // Check if email exists and add suffix if needed
        $baseEmail = $email . '@mail.com';
        $counter = 1;
        
        while (Employee::where('email', $baseEmail)->exists()) {
            $baseEmail = $email . '.' . $counter . '@mail.com';
            $counter++;
        }
        
        return $baseEmail;
    }

    /**
     * Log facility related errors with context
     */
    protected function logFacilityError(string $message, array $context = []): void
    {
        $logContext = array_merge([
            'timestamp' => now()->toDateTimeString(),
            'action' => 'facility_import',
        ], $context);

        Log::error($message, $logContext);
    }

    /**
     * Process facility data with improved error handling
     */
    protected function processFacility(array $entry): ?Facility
    {
        if (empty($entry['facility_id']) || empty($entry['facility'])) {
            return null;
        }

        // Only process if it's Ministry of Health
        if (strtolower($entry['facility']) !== 'ministry of health') {
            return null;
        }

        try {
            $facility = Facility::where('facility_id', $entry['facility_id'])
                              ->orWhere('name', 'Ministry of Health')
                              ->first();
            
            if (!$facility) {
                return Facility::create([
                    'facility_id' => $entry['facility_id'],
                    'name' => $entry['facility'],
                    'district' => $entry['district'] ?? null,
                    'region' => $entry['region'] ?? null,
                    'is_active' => true,
                ]);
            }
            
            // Update existing facility
            $facility->update([
                'name' => 'Ministry of Health',
                'district' => $entry['district'] ?? null,
                'region' => $entry['region'] ?? null,
                'is_active' => true,
            ]);
            
            return $facility;

        } catch (\Exception $e) {
            $this->logFacilityError('Error processing facility', [
                'error' => $e->getMessage(),
                'facility_data' => $entry
            ]);
            
            throw $e;
        }
    }

    protected function createDriver(Employee $employee, array $entry)
    {
        $licenseType = LicenseType::firstOrCreate(['name' => 'Default License']);

        Driver::updateOrCreate(
            [
                'employee_id' => $employee->id,
                'driver_code' => $entry['ipps'] ?? null
            ],
            [
                'employee_id' => $employee->id,
                'name' => $employee->name,
                'phone' => $entry['mobile'] ?? null,
                'license_type_id' => $licenseType->id,
                'license_num' => null, // Set to null instead of using NIN
                'license_issue_date' => now(),
                'license_expiry_date' => now()->addYears(5),
                'nid' => $entry['nin'] ?? null,
                'dob' => $this->validateAndParseDate($entry['birth_date']),
                'joining_date' => now(),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
