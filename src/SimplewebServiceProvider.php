<?php namespace Brackets\Simpleweb;

use Brackets\Simpleweb\Console\Commands\SimplewebInstall;
use Illuminate\Support\ServiceProvider;

class SimplewebServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands([
            SimplewebInstall::class,
        ]);

        if ($this->app->runningInConsole()) {
            if (! class_exists('FillDefaultUserAndPermissions')) {
                $timestamp = date('Y_m_d_His', time() + 5);

                $this->publishes([
                    __DIR__.'/../install-stubs/database/migrations/fill_default_user_and_permissions.php' => database_path('migrations').'/'.$timestamp.'_fill_default_user_and_permissions.php',
                ], 'migrations');
            }
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
