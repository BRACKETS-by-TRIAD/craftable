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

        sleep(2);

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
        $files->append('webpack.mix.js', "\n\n".$files->get(__DIR__ . '/../install-stubs/webpack.mix.js'));
        $this->info('Webpack configuration updated');

        // FIXME should it be here? Maybe it solves the problem Suballe was addressing
        $installDatepicker = new Process('npm install vue-flatpickr-component vue-quill-editor vue-notification vue-js-modal vue-multiselect moment --save');
        $installDatepicker->run();
        if (!$installDatepicker->isSuccessful()) {
                $this->error('Failed to install npm packages, please run "npm install vue-flatpickr-component vue-quill-editor vue-notification vue-js-modal vue-multiselect moment --save" manually.');
        }

        $this->info('SimpleWEB installed.');
    }

    private function strReplaceInFile($fileName, $find, $replaceWith) {
        $content = File::get($fileName);
        return File::put($fileName, str_replace($find, $replaceWith, $content));
    }
}