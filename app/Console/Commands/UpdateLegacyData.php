<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Patient; // Import your model

class UpdateLegacyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'legacy:update-patient-seating';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'updating patient seating available count';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Update legacy data logic here
        $this->updatePatientSeatingData();

        $this->info('Patient seating available data has been updated.');
        return 0;
    }

    /**
     * Function to handle the legacy data update logic.
     */
    protected function updatePatientSeatingData()
    {
        // Example: Updating legacy data for a model called `YourLegacyModel`
        $patients = Patient::whereNotNull('package_id')->get();

        foreach ($patients as $patient) {
            // Logic to update each record
            // For example, updating the 'status' field for legacy data
            $available_count = $patient->available_package_count();
            if ($available_count) {
                $patient->available_count = $available_count;
                $patient->save();
            }

            // Add any other logic you need for updating your data
        }
    }
}
