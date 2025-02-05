<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
    protected $description = 'Import employees from JSON file in public storage';

    protected function getDataFromJson(string $filename): array
    {
        if (!Storage::disk('public')->exists($filename)) {
            throw new \Exception("File not found in storage: {$filename}");
        }

        $json = Storage::disk('public')->get($filename);
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON format in employees file');
        }

        return $data;
    }

    public function handle()
    {
        $filename = 'employees.json';
        $startTime = microtime(true);
        $batchSize = (int) $this->option('batch-size');

        Log::info('Starting employee import process', ['batch_size' => $batchSize]);

        try {
            $data = $this->getDataFromJson($filename);
            $totalEntries = count($data);
            $batches = array_chunk($data, $batchSize);
            
            $successCount = 0;
            $errorCount = 0;
            $skippedCount = 0;

            $this->info("Starting import of {$totalEntries} employees in " . count($batches) . " batches...");
            $this->output->progressStart($totalEntries);

            foreach ($batches as $batchIndex => $batchData) {
                $this->info("\nProcessing batch " . ($batchIndex + 1) . " of " . count($batches));
                \DB::beginTransaction();

                try {
                    foreach ($batchData as $entry) {
                        if (empty($entry['surname']) || empty($entry['firstname'])) {
                            Log::warning('Skipped entry: Missing surname or firstname', $entry);
                            $skippedCount++;
                            $this->output->progressAdvance();
                            continue;
                        }

                        $department = Department::firstOrCreate(['name' => $entry['department'] ?? 'Unassigned']);
                        $position = Position::firstOrCreate(['name' => $entry['job'] ?? 'Unspecified']);
                        $facility = $this->processFacility($entry);

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
                    ['Skipped', $skippedCount],
                    ['Total Processed', $successCount + $errorCount + $skippedCount],
                ]
            );

            Log::info('Employee import completed', [
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'skipped_count' => $skippedCount,
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

        try {
            // First try to find by facility_id
            $facility = Facility::where('facility_id', $entry['facility_id'])->first();
            
            if (!$facility) {
                // If not found by facility_id, try to find by name
                $facility = Facility::where('name', $entry['facility'])->first();
                
                if (!$facility) {
                    // If facility doesn't exist at all, create it
                    return Facility::create([
                        'facility_id' => $entry['facility_id'],
                        'name' => $entry['facility'],
                        'district' => $entry['district'],
                        'region' => $entry['region'] ?? null,
                        'is_active' => true,
                    ]);
                }
                
                $this->logFacilityError('Facility exists with different facility_id', [
                    'existing_facility' => $facility->toArray(),
                    'import_data' => [
                        'facility_id' => $entry['facility_id'],
                        'name' => $entry['facility'],
                        'district' => $entry['district'],
                        'region' => $entry['region'] ?? null,
                    ]
                ]);
                
                return $facility;
            }
            
            // Update existing facility
            $facility->update([
                'name' => $entry['facility'],
                'district' => $entry['district'],
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

        // Create driver with employee_id included in both create and update arrays
        Driver::updateOrCreate(
            [
                'employee_id' => $employee->id,  // Include in the "where" clause
                'driver_code' => $entry['ipps'] ?? null
            ],
            [
                'employee_id' => $employee->id,  // Include in the data to be inserted/updated
                'name' => $employee->name,
                'phone' => $entry['mobile'] ?? null,
                'license_type_id' => $licenseType->id,
                'license_num' => $entry['nin'] ?? null,
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
