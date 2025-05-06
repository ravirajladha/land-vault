<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB; // Import the DB facade
use Carbon\Carbon;


class DeleteOldLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
  
    protected $signature = 'logs:delete-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete log entries older than 45 days';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cutOffDate = Carbon::now()->subDays(45);
        
        DB::table('log_changes')->where('created_at', '<', $cutOffDate)->delete();
        DB::table('http_request_logs')->where('created_at', '<', $cutOffDate)->delete();

        $this->info('Old logs deleted successfully.');
    }
}
