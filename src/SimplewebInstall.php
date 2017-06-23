<?php namespace Brackets\Simpleweb;

use Illuminate\Console\Command;

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
    public function handle()
    {

        $this->call('vendor:publish', [
            '--provider' => "Brackets\\Admin\\AdminProvider",
        ]);

        // FIXME finish this
        $this->info("Please edit a webpack.mix.js so it will look like this:\n\nmix.js('resources/assets/js/app.js', 'public/js')
    .js(['resources/assets/js/admin/admin.js', 'resources/assets/js/admin/coreui/app.js'], 'public/js/admin')
    .webpackConfig({
        resolve: {
            modules: [
                path.resolve(__dirname, 'vendor/brackets/admin/resources/assets/js'),
                'node_modules'
            ],
        }
    })
    .sass('resources/assets/sass/app.scss', 'public/css')
    .sass('resources/assets/sass/admin/app.scss', 'public/css/admin')
    .version();");

    }
}
