<?php namespace Brackets\Simpleweb;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

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

        //TODO publish migration from AdminAuth
        //TODO Remove User from App/User
        //TODO change config/auth.php to use App/Models/User::class

        $this->call('vendor:publish', [
            '--provider' => "Brackets\\Admin\\AdminProvider",
        ]);

        $files->append('webpack.mix.js', "\n\n".$files->get(__DIR__.'/../install-stubs/webpack.mix.js'));
        $this->info('Webpack configuration updated');

        $this->info('SimpleWEB installed.');

    }
}
