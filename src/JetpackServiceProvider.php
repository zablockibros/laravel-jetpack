<?php

namespace ZablockiBros\Jetpack;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use ZablockiBros\Jetpack\Console\GetModelColumns;

class JetpackServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
        }
    }

    /**
     * Register the package's publishable resources.
     */
    protected function registerPublishing()
    {
        // service provider
        $this->publishes([
            __DIR__ . '/Console/stubs/JetpackServiceProvider.stub' => app_path('Providers/JetpackServiceProvider.php'),
        ], 'jetpack-provider');

        // migrations
        if (! class_exists('CreateJetpackTables')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/../database/migrations/create_jetpack_tables.php.stub' => database_path('migrations/'.$timestamp.'_create_jetpack_tables.php'),
            ], 'jetpack-migrations');
        }

        // config
        $this->publishes([
            __DIR__ . '/../config/jetpack.php' => config_path('jetpack.php'),
        ], 'jetpack-config');

        // seeder
        $this->publishes([
            __DIR__ . '/Console/stubs/RolesAndPermissionsSeeder.stub' => database_path('seeds/RolesAndPermissionsSeeder.php'),
        ], 'jetpack-role-seeder');
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        // commands
        $this->commands([
            Console\MakeModel::class,
            Console\MakeResourceJob::class,
            Console\MakeController::class,
            Console\MakeModelFields::class,
            Console\MakePolicy::class,

            // dev
            GetModelColumns::class,
        ]);

        // Entrust
        if (config('jetpack.modules.roles.enable', false)) {
            $this->app->register('Spatie\Permission\PermissionServiceProvider');
        }
    }

    /**
     * todo
     *
     * Register the package routes.
     */
    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        });
    }

    /**
     * @return array
     */
    protected function routeConfiguration()
    {
        return [
            'namespace'  => 'ZablockiBros\Jetpack\Http\Controllers',
            'as'         => 'api.',
            'prefix'     => 'jetpack-api',
        ];
    }
}
