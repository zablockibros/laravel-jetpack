<?php

namespace ZablockiBros\Jetpack\Traits;

use Illuminate\Database\Eloquent\Model;

trait CanPerformActions
{
    /**
     * @param string      $action
     * @param null|string $modelClass
     *
     * @return bool
     */
    public function canPerform(string $action, ?string $modelClass = null): bool
    {
        return true;
    }

    /**
     * @param string                              $action
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return bool
     */
    public function canPerformOn(string $action, Model $model): bool
    {
        return true;
    }
}
