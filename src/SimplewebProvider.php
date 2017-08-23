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

        // In Laravel 5.5 these all is going to be deprecated
        $this->app->register(\Brackets\Admin\AdminProvider::class);
        $this->app->register(\Brackets\AdminListing\AdminListingServiceProvider::class);
        // FIXME this should not be here, because this package is dev dependency, but it's going to be solved with auto-discovery in Laravel 5.5
        $this->app->register(\Brackets\AdminGenerator\AdminGeneratorProvider::class);
        $this->app->register(\Brackets\AdminAuth\Providers\AdminAuthProvider::class);
        $this->app->register(\Brackets\AdminTranslations\AdminTranslationsProvider::class);
        $this->app->register(\Brackets\Media\MediaProvider::class);
        $this->app->register(\Brackets\Translatable\TranslatableProvider::class);
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
