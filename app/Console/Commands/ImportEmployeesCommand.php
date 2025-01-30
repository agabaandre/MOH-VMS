<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\Employee\Entities\Department;
use Modules\Employee\Entities\Driver;
use Modules\Employee\Entities\Employee;
use Modules\Employee\Entities\LicenseType;
use Modules\Employee\Entities\Position;

class ImportEmployeesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:employees';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import employees from JSON file in public storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filename = 'employees.json';
        $startTime = microtime(true);

        if (!Storage::disk('public')->exists($filename)) {
            $this->error("File not found in storage: {$filename}");
            return;
        }

        Log::info('Starting employee import process');

        $json = Storage::disk('public')->get($filename);
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('Invalid JSON format');
            Log::error('Invalid JSON format in employees file');
            return;
        }

        $totalEntries = count($data);
        $successCount = 0;
        $errorCount = 0;
        $skippedCount = 0;

        $this->info("Starting import of {$totalEntries} employees...");
        $this->output->progressStart($totalEntries);

        // Start database transaction
        \DB::beginTransaction();

        try {
            foreach ($data as $entry) {
                try {
                    // Skip invalid entries
                    if (empty($entry['surname']) || empty($entry['firstname'])) {
                        Log::warning('Skipped entry: Missing surname or firstname', $entry);
                        $skippedCount++;
                        $this->output->progressAdvance();
                        continue;
                    }

                    // Handle department relationship
                    $departmentName = $entry['department'] ?? 'Unassigned';
                    $department = Department::firstOrCreate(['name' => $departmentName]);

                    // Handle position relationship
                    $positionName = $entry['job'] ?? 'Unspecified';
                    $position = Position::firstOrCreate(['name' => $positionName]);

                    // Create full name
                    $fullName = trim(sprintf('%s %s %s',
                        $entry['surname'],
                        $entry['firstname'],
                        $entry['othername'] ?? ''
                    ));

                    $dob = $entry['birth_date'];
                    $dob = Carbon::parse($dob)->format('Y-m-d');
                    // dd($dob);

                    // Create/update employee record
                    $employee = Employee::updateOrCreate(
                        ['employee_code' => $entry['ipps']],
                        [
                            'name' => $fullName,
                            'department_id' => $department->id,
                            'position_id' => $position->id,
                            'phone' => $entry['mobile'] ?? null,
                            'email' => $entry['email'] ?? null,
                            'dob' => $dob,
                            'nid' => $entry['nin'] ?? null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );

                    // Check if the job is "Car Driver" and create a driver entry
                    if (strtolower($entry['job']) === 'car driver') {
                        $this->createDriver($employee, $entry);
                    }

                    $successCount++;
                    $this->output->progressAdvance();

                } catch (\Exception $e) {
                    $errorCount++;
                    Log::error('Error importing entry', [
                        'entry' => $entry,
                        'error' => $e->getMessage()
                    ]);
                    $this->error("Error: {$e->getMessage()}");
                    $this->output->progressAdvance();
                }
            }

            // Commit transaction if all entries processed
            \DB::commit();

            $this->output->progressFinish();

            $duration = round(microtime(true) - $startTime, 2);
            $this->info("\nImport completed in {$duration} seconds");
            $this->info("Success: {$successCount}, Errors: {$errorCount}, Skipped: {$skippedCount}");

            Log::info('Employee import completed', [
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'skipped_count' => $skippedCount,
                'duration_seconds' => $duration
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            $this->error("\nImport failed. All changes rolled back.");
            Log::error('Employee import failed - transaction rolled back', [
                'error' => $e->getMessage(),
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'skipped_count' => $skippedCount
            ]);
            throw $e;
        }
    }

    /**
     * Create a driver entry for the employee.
     *
     * @param Employee $employee
     * @param array $entry
     */
    /**
     * Validate and parse a date string
     *
     * @param string|null $dateString
     * @return string|null
     */
    protected function validateAndParseDate(?string $dateString): ?string
    {
        if (empty($dateString)) {
            return null;
        }

        // Check if date is in valid format and within reasonable range
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateString) ||
            $dateString < '1900-01-01' || 
            $dateString > now()->addYears(1)->toDateString()) {
            Log::warning('Invalid date format or range', ['date' => $dateString]);
            return null;
        }

        try {
            return Carbon::parse($dateString)->toDateString();
        } catch (\Exception $e) {
            Log::warning('Failed to parse date', [
                'date' => $dateString,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    protected function createDriver(Employee $employee, array $entry)
    {
        // Assuming you have a default license type for drivers
        $licenseType = LicenseType::firstOrCreate(['name' => 'Default License']);

        Driver::updateOrCreate(
            ['employee_id' => $employee->id],
            [
                'name' => $employee->name,
                'driver_code' => $entry['ipps'] ?? null,
                'phone' => $entry['mobile'] ?? null,
                'license_type_id' => $licenseType->id,
                'license_num' => $entry['nin'] ?? null, // Using NIN as license number
                'license_issue_date' => now(), // Default to current date
                'license_expiry_date' => now()->addYears(5), // Default to 5 years from now
                'nid' => $entry['nin'] ?? null,
                'dob' => !empty($entry['birth_date']) ?
                    Carbon::parse($entry['birth_date'])->toDateString() : null,
                'joining_date' => now(), // Default to current date
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
