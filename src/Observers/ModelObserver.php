<?php

namespace ZablockiBros\Jetpack\Observers;

use Illuminate\Database\Eloquent\Model;

class ModelObserver
{
    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function __call(Model $model)
    {

    }
}
