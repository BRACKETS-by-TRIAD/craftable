<?php namespace Brackets\Craftable;

use Brackets\Craftable\Console\Commands\CraftableInstall;
use Illuminate\Support\ServiceProvider;

class CraftableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands([
            CraftableInstall::class,
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
