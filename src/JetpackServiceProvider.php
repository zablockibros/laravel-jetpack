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

        // config
        if (! class_exists('CreateJetpackTables')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/../database/migrations/create_jetpack_tables.php.stub' => database_path('migrations/'.$timestamp.'_create_jetpack_tables.php'),
            ], 'jetpack-migrations');
        }

        // config
        $this->publishes([
            __DIR__ . '/config/jetpack.php' => config_path('jetpack.php'),
        ], 'jetpack-config');

        if (! $this->app->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__ . '/../config/jetpack.php', 'jetpack');
        }
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        // commands
        $this->commands([
            Console\MakeModel::class,
            Console\MakeController::class,
            Console\MakeModelFields::class,

            // dev
            GetModelColumns::class,
        ]);

        // Entrust
        if (config('jetpack.modules.roles_and_permissions.enable', false)) {
            $this->app->register('Zizaco\Entrust\EntrustServiceProvider');

            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Entrust', 'Zizaco\Entrust\EntrustFacade');
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
