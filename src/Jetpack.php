<?php

namespace ZablockiBros\Jetpack;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use ZablockiBros\Jetpack\Events\Running;
use ZablockiBros\Jetpack\Models\Actions\Action;
use ZablockiBros\Jetpack\Models\BaseModel;
use ZablockiBros\Jetpack\Models\Columns\Column;
use ZablockiBros\Jetpack\Observers\ModelObserver;

class Jetpack
{
    /**
     * Jetpack/Models/BaseModel[]
     *
     * @var array
     */
    protected static $models = [];

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
    public static function bootModels()
    {
        collect(static::$models)
            ->each(function (BaseModel $baseModel) {
                $baseModel::boot();
            });
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

    /**
     * @param string $className
     *
     * @return null|\ZablockiBros\Jetpack\Models\BaseModel
     */
    public static function getModel(string $className): ?BaseModel
    {
        return collect(static::$models)
            ->first(function (BaseModel $baseModel) use ($className) {
                return class_basename($baseModel::$model) === class_basename($className);
            });
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model         $model
     * @param \ZablockiBros\Jetpack\Models\Actions\Action $action
     */
    private static function defineGateEvent(Model $model, Action $action)
    {
        $event    = $action->name();
        $callback = $action->authorizeCallback();
        $table    = $model->getTable();

        Gate::define("$table.$event", $callback);
    }
}
