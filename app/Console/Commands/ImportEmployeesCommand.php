<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\Employee\Entities\Department;
use Modules\Employee\Entities\Employee;
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

                    // Create/update employee record
                    Employee::updateOrCreate(
                    ['employee_code' => $entry['ipps']],
                    [
                        'name' => $fullName,
                        'department_id' => $department->id,
                        'position_id' => $position->id,
                        'phone' => $entry['mobile'] ?? null,
                        'email' => $entry['email'] ?? null,
                        'dob' => !empty($entry['birth_date']) ?
                            Carbon::parse($entry['birth_date'])->toDateString() : null,
                        'nid' => $entry['nin'] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

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
}
