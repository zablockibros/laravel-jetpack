<?php

namespace ZablockiBros\Jetpack\Models;

use Illuminate\Database\Eloquent\Model;
use ZablockiBros\Jetpack\Models\Actions\Action;
use ZablockiBros\Jetpack\Models\Columns\Column;
use ZablockiBros\Jetpack\Models\Relationships\Relation;
use ZablockiBros\Jetpack\Observers\ModelObserver;

abstract class BaseModel
{
    /**
     * @var string
     */
    public $model;

    /**
     * @var bool
     */
    public $hasFields = true;

    /**
     * @var bool
     */
    public $validates = true;

    /**
     * @var bool
     */
    public $authorizes = true;

    /**
     * @var bool
     */
    public $relates = true;

    /**
     * @var bool
     */
    public $firesEvents = true;

    /**
     * @override
     *
     * @return array
     */
    public function columns()
    {
        return [
            //Column::string(''),
        ];
    }

    /**
     * @override
     *
     * @return array
     */
    public function relations()
    {
        return [
            //Relation::hasMany(''),
        ];
    }

    /**
     * @override
     *
     * @return array
     */
    public function actions()
    {
        return [
            //Action::create(),
        ];
    }

    /**
     * Boot
     */
    public static function boot()
    {
        $model = new static::$model();

        static::observeModel($model);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public static function observeModel(Model $model)
    {
        $model::observe(new ModelObserver(new static()));
    }

    /**
     * @return array
     */
    public function columnNames(): array
    {
        return collect($this->columns())
            ->map(function (Column $column) {
                return $column->name();
            })
            ->toArray();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return array
     */
    public function getFieldAttributes(Model $model)
    {
        return collect($model->getAttributes())
            ->only($this->columnNames())
            ->toArray();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return array
     */
    public function getAttributes(Model $model)
    {
        return collect($model->getAttributes())
            ->except($this->columnNames())
            ->toArray();
    }
}
