<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

class DeleteDynamicTables extends Command
{
    protected $signature = 'dynamic-tables:delete';
    protected $description = 'Delete all dynamically created tables and their model files';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Fetch all dynamic table names from your 'master_doc_types' table
        $tables = DB::table('master_doc_types')->pluck('name');

        foreach ($tables as $table) {
            // Check if the table exists before trying to drop it
            if (Schema::hasTable($table)) {
                Schema::drop($table);
                $this->info("Dropped table: {$table}");
            }

            // Now delete the corresponding model file
            $modelFile = app_path("Models/" . ucfirst($table) . ".php");
            if (File::exists($modelFile)) {
                File::delete($modelFile);
                $this->info("Deleted model file: {$modelFile}");
            }
        }

        $this->info('All dynamic tables and model files have been deleted.');
    }
}
