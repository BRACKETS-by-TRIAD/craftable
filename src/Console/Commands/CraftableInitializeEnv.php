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
     * Replace string in file
     *
     * @param string $fileName
     * @param string $find
     * @param string $replaceWith
     * @return int|bool
     */
    private function strReplaceInFile($fileName, $find, $replaceWith)
    {
        $content = File::get($fileName);
        return File::put($fileName, str_replace($find, $replaceWith, $content));
    }

    /**
     * If default database name in env is present and interaction mode is on,
     * asks for database settings. Not provided values will not be overwritten.
     *
     * @return void
     */
    private function getDbSettings(): void
    {
        if (env('DB_DATABASE') === 'homestead' && $this->input->isInteractive()) {
            $dbConnection = $this->choice('What database driver do you use?', ['mysql', 'pgsql'], 1);
            if (!empty($dbConnection)) {
                $this->strReplaceInFile(
                    base_path('.env'),
                    'DB_CONNECTION=mysql',
                    'DB_CONNECTION=' . $dbConnection
                );
            }
            $dbHost = $this->anticipate('What is your database host?', ['localhost', '127.0.0.1'], '127.0.0.1');
            if (!empty($dbHost)) {
                $this->strReplaceInFile(
                    base_path('.env'),
                    'DB_HOST=127.0.0.1',
                    'DB_HOST=' . $dbHost
                );
            }
            $dbPort = $this->anticipate(
                'What is your database port?',
                ['3306', '5432'],
                env('DB_DATABASE') === 'mysql' ? '3306' : '5432'
            );
            if (!empty($dbPort)) {
                $this->strReplaceInFile(
                    base_path('.env'),
                    'DB_PORT=3306',
                    'DB_PORT=' . $dbPort
                );
            }
            $DbDatabase = $this->ask('What is your database name?', 'homestead');
            if (!empty($DbDatabase)) {
                $this->strReplaceInFile(
                    base_path('.env'),
                    'DB_DATABASE=homestead',
                    'DB_DATABASE=' . $DbDatabase
                );
            }
            $dbUsername = $this->ask('What is your database user name?', 'homestead');
            if (!empty($dbUsername)) {
                $this->strReplaceInFile(
                    base_path('.env'),
                    'DB_USERNAME=homestead',
                    'DB_USERNAME=' . $dbUsername
                );
            }
            $dbPassword = $this->ask('What is your database user password?', 'secret');
            if (!empty($dbPassword)) {
                $this->strReplaceInFile(
                    base_path('.env'),
                    'DB_PASSWORD=secret',
                    'DB_PASSWORD="' . $dbPassword . '"'
                );
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
            $this->strReplaceInFile(
                base_path('.env'),
                'APP_NAME=Laravel',
                'APP_NAME="Craftable"'
            );
            $this->strReplaceInFile(
                base_path('.env.example'),
                'APP_NAME=Laravel',
                'APP_NAME="Craftable"'
            );
        }
    }
}
