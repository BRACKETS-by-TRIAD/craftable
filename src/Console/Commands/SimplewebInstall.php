<?php namespace Brackets\Simpleweb\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class SimplewebInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simpleweb:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install a SimpleWEB (brackets/simpleweb) instance';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Filesystem $files)
    {
        $this->info('Crafting SimpleWEB...');

        $this->publishAllVendors();

        $this->call('admin-ui:install');

//        $this->call('admin-auth:install');

        $this->call('admin-translations:install');

        $this->generateUserStuff($files);

        $this->scanAndSaveTranslations();

        $this->frontendAdjustments($files);

        $this->info('SimpleWEB crafted :)');
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

        // TODO replace with admin-auth:install
        //Admin Auth
        $this->call('vendor:publish', [
            '--provider' => "Brackets\\AdminAuth\\AdminAuthServiceProvider",
        ]);

        //Media
        $this->call('vendor:publish', [
            '--provider' => "Brackets\\Media\\MediaServiceProvider",
        ]);

        //Simpleweb
        $this->call('vendor:publish', [
            '--provider' => "Brackets\\Simpleweb\\SimplewebServiceProvider",
        ]);
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

    /**
     * @param Filesystem $files
     */
    private function frontendAdjustments(Filesystem $files) {
        // TODO replace with admin-auth:install
        // register auth assets
        $files->append(resource_path('assets/admin/js/index.js'), "\nimport 'auth';\n");
        $this->info('Admin Auth assets registered');
    }
}