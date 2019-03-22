<?php

namespace Brackets\Craftable;

use Brackets\Craftable\Console\Commands\CraftableInitializeEnv;
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
            CraftableInitializeEnv::class,
            CraftableInstall::class,
        ]);

        if ($this->app->runningInConsole()) {
            if (!class_exists('FillDefaultAdminUserAndPermissions')) {
                $timestamp = date('Y_m_d_His', time() + 5);

                $this->publishes([
                    __DIR__ . '/../install-stubs/database/migrations/fill_default_admin_user_and_permissions.php' => database_path('migrations') . '/' . $timestamp . '_fill_default_admin_user_and_permissions.php',
                ], 'migrations');
            }

            if (!file_exists(storage_path()."/images/avatar.png")) {
                $this->publishes([
                    __DIR__ . '/../resources/images/avatar.png' => storage_path().'/images',
                ], 'images');
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
