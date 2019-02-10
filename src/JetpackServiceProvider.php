<?php

namespace ZablockiBros\Jetpack;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class JetpackServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->createMigrations();
        $this->setUpConfig();
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        $this->commands([
            Console\MakeModel::class,
            Console\MakeController::class,
        ]);
    }

    /**
     * @return void
     */
    protected function createMigrations()
    {
        if (! class_exists('CreateJetpackTables')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/../database/migrations/create_jetpack_tables.php.stub' => database_path('migrations/'.$timestamp.'_create_jetpack_tables.php'),
            ], 'migrations');
        }

        return;
    }

    /**
     * @return void
     */
    protected function setUpConfig()
    {
        $source = dirname(__DIR__) . '/config/jetpack.php';

        $this->publishes([$source => config_path('jetpack.php')], 'config');
        $this->mergeConfigFrom($source, 'sluggable');

        return;
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
