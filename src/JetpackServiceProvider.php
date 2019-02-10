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
        if (! class_exists('CreateJetpackTables')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/../database/migrations/create_jetpack_tables.php.stub' => database_path('migrations/'.$timestamp.'_create_jetpack_tables.php'),
            ], 'migrations');
        }
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }
}
