<?php namespace Brackets\Craftable\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class CraftableInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'craftable:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install a Craftable (brackets/craftable) instance';

    /**
     * Password for generated default admin
     *
     * @var string
     */
    protected $password = 'best package ever';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Filesystem $files)
    {
        $this->info('Installing Craftable...');

//        $this->getDbSettings();
//
//        $this->publishAllVendors();
//
//        $this->generatePasswordAndUpdateMigration();
//
//        $this->call('admin-ui:install');
//
//        $this->call('admin-auth:install');
//
//        $this->generateUserStuff($files);
//
//        $this->call('admin-translations:install');
//
//        $this->scanAndSaveTranslations();
//
//        $this->call('admin-listing:install');

        //TODO run npm install
        //TODO run npm run dev
//
//        $this->comment('Admin password is: ' . $this->password);
//
//        $this->info('Craftable installed.');
    }

    private function strReplaceInFile($fileName, $find, $replaceWith) {
        $content = File::get($fileName);
        return File::put($fileName, str_replace($find, $replaceWith, $content));
    }

    private function publishAllVendors() {
        //Spatie Permission
        $this->call('vendor:publish', [
            '--provider' => 'Spatie\\Permission\\PermissionServiceProvider',
            '--tag' => 'migrations'
        ]);
        $this->call('vendor:publish', [
            '--provider' => 'Spatie\\Permission\\PermissionServiceProvider',
            '--tag' => 'config'
        ]);

        //Spatie Backup
        $this->call('vendor:publish', [
            '--provider' => "Spatie\\Backup\\BackupServiceProvider",
        ]);

        //Media
        $this->call('vendor:publish', [
            '--provider' => 'Spatie\\MediaLibrary\\MediaLibraryServiceProvider',
            '--tag' => 'migrations'
        ]);
        $this->call('vendor:publish', [
            '--provider' => "Brackets\\Media\\MediaServiceProvider",
        ]);

        //Craftable
        $this->call('vendor:publish', [
            '--provider' => "Brackets\\Craftable\\CraftableServiceProvider",
        ]);
    }

    private function generatePasswordAndUpdateMigration()
    {
        $this->password = str_random(10);

        $files = File::allFiles(database_path('migrations'));
        foreach ($files as $file)
        {
            if(strpos($file->getFilename(), 'fill_default_user_and_permissions.php') !== false) {
                //change database/migrations/*fill_default_user_and_permissions.php to use new password
                $this->strReplaceInFile(database_path('migrations/'.$file->getFilename()),
                    "best package ever",
                    "".$this->password."");
                break;
            }
        }
    }

    private function generateUserStuff(Filesystem $files) {
        // TODO this is probably redundant?
        // Migrate
        $this->call('migrate');

        // Generate User CRUD (with new model)
        $this->call('admin:generate:user', [
            '--model-name' => "App\\Models\\User",
            '--generate-model' => true,
            '--force' => true,
        ]);

        //change config/auth.php to use App/Models/User::class
        $this->strReplaceInFile(config_path('auth.php'),
            "App\\User::class",
            "App\\Models\\User::class");

        // Remove User from App/User
        $files->delete(app_path('User.php'));

        // Generate user profile
        $this->call('admin:generate:user:profile');
    }

    // TODO should it still be here?
    private function scanAndSaveTranslations() {
        // Scan translations
        $this->info('Scanning codebase and storing all translations');

        $this->strReplaceInFile(config_path('admin-translations.php'),
            '// here you can add your own directories',
            '// here you can add your own directories
        // base_path(\'routes\'), // uncomment if you have translations in your routes i.e. you have localized URLs
        base_path(\'vendor/brackets/admin-auth/src\'),
        base_path(\'vendor/brackets/admin-auth/resources\'),');

        $this->call('admin-translations:scan-and-save', [
            'paths' => array_merge(config('admin-translations.scanned_directories'), ['vendor/brackets/admin-auth/src', 'vendor/brackets/admin-auth/resources']),
        ]);
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
            $dbHost = $this->anticipate('What is your database host?', ['localhost', '127.0.0.1']);
            if(!empty($dbHost)) {
                $this->strReplaceInFile(base_path('.env'),
                    'DB_HOST=127.0.0.1',
                    'DB_HOST='.$dbHost);
            }
            $dbPort = $this->anticipate('What is your database port?', ['3306', '5432']);
            if(!empty($dbPort)) {
                $this->strReplaceInFile(base_path('.env'),
                    'DB_PORT=3306',
                    'DB_PORT='.$dbPort);
            }
            $DbDatabase = $this->ask('What is your database name?');
            if(!empty($DbDatabase)) {
                $this->strReplaceInFile(base_path('.env'),
                    'DB_DATABASE=homestead',
                    'DB_DATABASE='.$DbDatabase);
            }
            $dbUsername = $this->ask('What is your database user name?');
            if(!empty($dbUsername)) {
                $this->strReplaceInFile(base_path('.env'),
                    'DB_USERNAME=homestead',
                    'DB_USERNAME='.$dbUsername);
            }
            $dbPassword = $this->secret('What is your database user password?');
            if(!empty($dbPassword)) {
                $this->strReplaceInFile(base_path('.env'),
                    'DB_PASSWORD=secret',
                    'DB_PASSWORD='.$dbPassword);
            }
        }

    }

}