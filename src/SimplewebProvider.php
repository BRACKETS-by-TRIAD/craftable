<?php namespace Brackets\Simpleweb;

use Illuminate\Support\ServiceProvider;

class SimplewebProvider extends ServiceProvider
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

        if (! class_exists('FillDefaultUserAndPermissions')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/../install-stubs/database/migrations/fill_default_user_and_permissions.php' => database_path('migrations').'/'.$timestamp.'fill_default_user_and_permissions.php',
            ], 'migrations');
        }

        $this->app->register(\Spatie\Permission\PermissionServiceProvider::class);
        $this->app->register(\Brackets\Admin\AdminProvider::class);
        $this->app->register(\Brackets\AdminGenerator\AdminGeneratorProvider::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
