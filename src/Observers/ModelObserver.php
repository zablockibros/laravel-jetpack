<?php

namespace ZablockiBros\Jetpack\Observers;

use Illuminate\Database\Eloquent\Model;
use ZablockiBros\Jetpack\Models\BaseModel;

class ModelObserver
{
    /**
     * @var BaseModel
     */
    protected $baseModel;

    /**
     * ModelObserver constructor.
     *
     * @param \ZablockiBros\Jetpack\Models\BaseModel $baseModel
     */
    public function __construct(BaseModel $baseModel)
    {
        $this->baseModel = $baseModel;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function saving(Model $model)
    {
        if (! $this->baseModel->hasFields) {
            return true;
        }

        //$model->setAttribute('fields', $this->baseModel->getFieldAttributes($model));
        //$model->setRawAttributes($this->baseModel->getAttributes($model));

        return true;
    }
}
