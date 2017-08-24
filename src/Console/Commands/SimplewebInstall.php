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
    protected $description = 'Install a SimpleWEB instance';

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
     * @return mixed
     */
    public function handle(Filesystem $files)
    {
        $this->info('Crafting Simpleweb...');

        //TODO publish migration, config and lang

        /**
         * Publish all
         */
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

        //Admin
        $this->call('vendor:publish', [
            '--provider' => "Brackets\\Admin\\AdminServiceProvider",
        ]);

        //Admin Auth
        $this->call('vendor:publish', [
            '--provider' => "Brackets\\AdminAuth\\AdminAuthServiceProvider",
        ]);

        //Admin Translations
        $this->call('vendor:publish', [
            '--provider' => "Brackets\\AdminTranslations\\AdminTranslationsServiceProvider",
        ]);

        //Media
        $this->call('vendor:publish', [
            '--provider' => "Brackets\\Media\\MediaServiceProvider",
        ]);

        //Media
        $this->call('vendor:publish', [
            '--provider' => "Brackets\\Translatable\\TranslatableServiceProvider",
        ]);

        //Simpleweb
        $this->call('vendor:publish', [
            '--provider' => "Brackets\\Simpleweb\\SimplewebServiceProvider",
        ]);

        /**
         * Migrate
         */
        $this->call('migrate');

        //TODO Generate user with model
        $this->call('admin:generate:user', [
            '--model-name' => "App\\Models\\User",
            '--generate-model' => true,
            '--force' => true,
        ]);

        //change config/auth.php to use App/Models/User::class
        $this->strReplaceInFile(config_path('auth.php'),
            "App\\User::class",
            "App\\Models\\User::class");

        //TODO Remove User from App/User
        $files->delete(app_path('User.php'));

        /**
         * Change webpack
         */
        $files->append('webpack.mix.js', "\n\n".$files->get(__DIR__ . '/../../../install-stubs/webpack.mix.js'));
        $this->info('Webpack configuration updated');

        //Change package.json
        $this->info('Changing package.json');
        $packageJsonFile = base_path('package.json');
        $packageJson = $files->get($packageJsonFile);
        $packageJsonContent = json_decode($packageJson, JSON_OBJECT_AS_ARRAY);
        $packageJsonContent['devDependencies']['vee-validate'] = '^2.0.0-rc.13';
        $packageJsonContent['devDependencies']['vue'] = '^2.3.4';
        $packageJsonContent['devDependencies']['vue-flatpickr-component'] = '^2.4.1';
        $packageJsonContent['devDependencies']['vue-js-modal'] = '^1.2.8';
        $packageJsonContent['devDependencies']['vue-multiselect'] = '^2.0.2';
        $packageJsonContent['devDependencies']['vue-notification'] = '^1.3.2';
        $packageJsonContent['devDependencies']['vue-quill-editor'] = '^2.3.0';
        $packageJsonContent['devDependencies']['moment'] = '^2.18.1';
        $files->put($packageJsonFile, json_encode($packageJsonContent, JSON_PRETTY_PRINT));
        $this->info('package.json changed');

        $this->info('SimpleWEB installed.');
    }

    private function strReplaceInFile($fileName, $find, $replaceWith) {
        $content = File::get($fileName);
        return File::put($fileName, str_replace($find, $replaceWith, $content));
    }
}