<?php

namespace ZablockiBros\Jetpack\Traits;

use Illuminate\Database\Eloquent\Relations\Relation;
use ZablockiBros\Jetpack\Contracts\OwnableInterface;

trait IsOwner
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function ownables(): Relation
    {

    }

    /**
     * @param \ZablockiBros\Jetpack\Contracts\OwnableInterface $model
     *
     * @return bool
     */
    public function owns(OwnableInterface $model): bool
    {

    }

    /**
     * @param \ZablockiBros\Jetpack\Contracts\OwnableInterface $model
     *
     * @return mixed
     */
    public function makeOwner(OwnableInterface $model)
    {

    }
}
