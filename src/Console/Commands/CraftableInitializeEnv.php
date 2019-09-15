<?php

namespace Brackets\Craftable\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CraftableInitializeEnv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'craftable:init-env';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize database environment variables';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->info('Initializing database environment variables...');

        $this->getDbSettings();

        $this->info('Database environment variables initialized.');

        $this->setApplicationName();
    }

    /**
     * Update .env setting
     *
     * @param string $key
     * @param string $value
     * @param string $fileName 
     * @return int|bool
     */
    private function updateEnv($key, $value, $fileName = '.env')
    {
        $fileName = base_path($fileName);
        $content = File::get($fileName);
        return File::put($fileName, preg_replace('/' . $key . '=.*/', $key . '=' . $value, $content));
    }

    /**
     * If default database values in .env are present and interaction mode is on,
     * asks for database settings. Values not provided will not be overwritten.
     *
     * @return void
     */
    private function getDbSettings(): void
    {
        if ($this->isDefaultDatabaseEnv() && $this->input->isInteractive()) {
            $dbConnection = $this->choice('What database driver do you use?', ['mysql', 'pgsql'], 0);
            if (!empty($dbConnection)) {
                $this->updateEnv('DB_CONNECTION', $dbConnection);
            }

            $dbHost = $this->anticipate('What is your database host?', ['localhost', '127.0.0.1'], '127.0.0.1');
            if (!empty($dbHost)) {
                $this->updateEnv('DB_HOST', $dbHost);
            }

            $dbPort = $this->anticipate(
                'What is your database port?',
                ['3306', '5432'],
                env('DB_CONNECTION') === 'mysql' ? '3306' : '5432'
            );
            if (!empty($dbPort)) {
                $this->updateEnv('DB_PORT', $dbPort);
            }

            $dbDatabase = $this->anticipate('What is your database name?', 
                ['laravel', 'homestead'],
                'laravel'
            );
            if (!empty($dbDatabase)) {
                $this->updateEnv('DB_DATABASE', $dbDatabase);
            }

            $dbUsername = $this->anticipate('What is your database user name?',
                ['root', 'homestead'], 
                'root'
            );
            if (!empty($dbUsername)) {
                $this->updateEnv('DB_USERNAME', $dbUsername);
            }

            $dbPassword = $this->secret('What is your database user password?', 'secret');
            if (!empty($dbPassword)) {
                $this->updateEnv('DB_PASSWORD', $dbPassword);
            }
        }
    }

    /**
     * Change default application name from Laravel to Craftable
     *
     * @return void
     */
    private function setApplicationName(): void
    {
        if (env('APP_NAME') === 'Laravel') {
            $this->updateEnv('APP_NAME', 'Craftable');
            $this->updateEnv('APP_NAME', 'Craftable', '.env.example');
        }
    }

    /**
     * Determines if the .env file has default database settings
     * 
     * @return boolean
     */
    private function isDefaultDatabaseEnv(): bool
    {
        if ( 
            version_compare(app()::VERSION, '5.8.35', '<') && 
                (env('DB_DATABASE') === 'homestead' && 
                    env('DB_USERNAME') === 'homestead') ||
            version_compare(app()::VERSION, '5.8.35', '>=') && 
                (env('DB_DATABASE') === 'laravel' && 
                    env('DB_USERNAME') === 'root') 
        ) {
            return true;
        }

        return false;
    }
}
