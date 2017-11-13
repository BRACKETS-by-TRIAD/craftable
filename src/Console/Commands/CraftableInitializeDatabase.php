<?php namespace Brackets\Craftable\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class CraftableInitializeDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'craftable:initDb';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize .env database settings';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Filesystem $files)
    {
        $this->info('Initializing Database...');

        $this->getDbSettings();

        $this->info('Database settings initialized.');
    }

    private function strReplaceInFile($fileName, $find, $replaceWith) {
        $content = File::get($fileName);
        return File::put($fileName, str_replace($find, $replaceWith, $content));
    }

    /*
     * If default database name in env is present and interaction mode is on,
     * asks for database settings. Not provided values will not be overwritten.
     */
    private function getDbSettings()
    {
        if(env('DB_DATABASE') == 'homestead' && $this->input->isInteractive()) {
            $dbConnection = $this->choice('What database driver do you use?', ['mysql', 'pgsql'], 1);
            if(!empty($dbConnection)) {
                $this->strReplaceInFile(base_path('.env'),
                    'DB_CONNECTION=mysql',
                    'DB_CONNECTION='.$dbConnection);
            }
            $dbHost = $this->anticipate('What is your database host?', ['localhost', '127.0.0.1'], '127.0.0.1');
            if(!empty($dbHost)) {
                $this->strReplaceInFile(base_path('.env'),
                    'DB_HOST=127.0.0.1',
                    'DB_HOST='.$dbHost);
            }
            $dbPort = $this->anticipate('What is your database port?', ['3306', '5432'], env('DB_DATABASE') == 'mysql' ? '3306' : '5432');
            if(!empty($dbPort)) {
                $this->strReplaceInFile(base_path('.env'),
                    'DB_PORT=3306',
                    'DB_PORT='.$dbPort);
            }
            $DbDatabase = $this->ask('What is your database name?', 'homestead');
            if(!empty($DbDatabase)) {
                $this->strReplaceInFile(base_path('.env'),
                    'DB_DATABASE=homestead',
                    'DB_DATABASE='.$DbDatabase);
            }
            $dbUsername = $this->ask('What is your database user name?', 'homestead');
            if(!empty($dbUsername)) {
                $this->strReplaceInFile(base_path('.env'),
                    'DB_USERNAME=homestead',
                    'DB_USERNAME='.$dbUsername);
            }
            $dbPassword = $this->ask('What is your database user password?', 'secret');
            if(!empty($dbPassword)) {
                $this->strReplaceInFile(base_path('.env'),
                    'DB_PASSWORD=secret',
                    'DB_PASSWORD='.$dbPassword);
            }
        }
    }
}