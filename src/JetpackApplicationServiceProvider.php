<?php

namespace ZablockiBros\Jetpack;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use ZablockiBros\Jetpack\Events\Running;

class JetpackApplicationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->routes();

        Jetpack::running(function (Running $event) {
            // auth
            $this->authorization();

            // models
            Jetpack::models($this->models());
            Jetpack::observeModels();
        });
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
    protected function routes()
    {
        return;
    }

    /**
     * @return void
     */
    protected function authorization()
    {
        return;
    }

    /**
     * @return array
     */
    protected function models()
    {
        return [];
    }
}
