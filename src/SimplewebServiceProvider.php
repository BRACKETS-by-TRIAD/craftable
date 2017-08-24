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
                $timestamp = date('Y_m_d_His', time());

                $this->publishes([
                    __DIR__.'/../install-stubs/database/migrations/fill_default_user_and_permissions.php' => database_path('migrations').'/'.$timestamp.'fill_default_user_and_permissions.php',
                ], 'migrations');
            }
        }

        // In Laravel 5.5 these all is going to be deprecated
        $this->app->register(\Brackets\Admin\AdminServiceProvider::class);
        $this->app->register(\Brackets\AdminListing\AdminListingServiceProvider::class);
        // FIXME this should not be here, because this package is dev dependency, but it's going to be solved with auto-discovery in Laravel 5.5
        $this->app->register(\Brackets\AdminGenerator\AdminGeneratorServiceProvider::class);
        $this->app->register(\Brackets\AdminAuth\AdminAuthServiceProvider::class);
        $this->app->register(\Brackets\AdminTranslations\AdminTranslationsServiceProvider::class);
        $this->app->register(\Brackets\Media\MediaServiceProvider::class);
        $this->app->register(\Brackets\Translatable\TranslatableServiceProvider::class);
//        $this->app->register(\Spatie\Permission\PermissionServiceProvider::class);
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
