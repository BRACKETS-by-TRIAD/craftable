<?php

namespace Brackets\Craftable\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CraftableTestDBConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'craftable:test-db-connection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the database connection';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->info('Testing the database connection...');

        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $this->error("Could not connect to the database.  Please check your configuration. Error: " . $e->getMessage());
            return ;
        }

        $this->info('...connection OK');
    }
}
