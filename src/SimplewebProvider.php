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
