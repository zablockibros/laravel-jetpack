<?php

namespace ZablockiBros\Jetpack;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use ZablockiBros\Jetpack\Events\Running;
use ZablockiBros\Jetpack\Observers\ModelObserver;

class Jetpack
{
    /**
     * @var array
     */
    protected static $models = [];

    /**
     * @var array
     */
    protected static $modelEvents = [
        'retrieved',
        'creating',
        'created',
        'updating',
        'updated',
        'saving',
        'saved',
        'restoring',
        'restored',
        'deleting',
        'deleted',
        'forceDeleted',
    ];

    /**
     * @param array $models
     */
    public static function models(array $models)
    {
        static::$models = array_merge(
            static::$models,
            $models
        );
    }

    /**
     * @return void
     */
    public static function observeModels()
    {
        Model::observe(new ModelObserver());
    }

    /**
     * Register an event listener for the Jetpack "running" event.
     *
     * @param \Closure|string $callback
     */
    public static function running($callback)
    {
        Event::listen(Running::class, $callback);
    }
}
