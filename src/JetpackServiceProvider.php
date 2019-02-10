<?php

namespace ZablockiBros\Jetpack;

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
        //
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
        $source = dirname(__DIR__) . '/resources/config/jetpack.php';

        $this->publishes([$source => config_path('jetpack.php')], 'config');

        $this->mergeConfigFrom($source, 'sluggable');

        return;
    }
}
