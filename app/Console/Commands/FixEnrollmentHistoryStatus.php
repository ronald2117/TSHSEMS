<?php

namespace App\Console\Commands;

use App\Models\StudentEnrollmentHistory;
use Illuminate\Console\Command;

class FixEnrollmentHistoryStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enrollment:fix-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix enrollment history status values to use proper capitalization';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing enrollment history status values...');

        // Fix lowercase 'enrolled' to 'Enrolled'
        $enrolled = StudentEnrollmentHistory::where('status', 'enrolled')->update(['status' => 'Enrolled']);
        $this->info("Fixed {$enrolled} records from 'enrolled' to 'Enrolled'");

        // Fix lowercase 'withdrawn' to 'Withdrawn'
        $withdrawn = StudentEnrollmentHistory::where('status', 'withdrawn')->update(['status' => 'Withdrawn']);
        $this->info("Fixed {$withdrawn} records from 'withdrawn' to 'Withdrawn'");

        // Fix lowercase 'transferred' to 'Transferred'
        $transferred = StudentEnrollmentHistory::where('status', 'transferred')->update(['status' => 'Transferred']);
        $this->info("Fixed {$transferred} records from 'transferred' to 'Transferred'");

        $this->info('Done! All enrollment history status values have been fixed.');

        return Command::SUCCESS;
    }
}
