<?php

namespace ZablockiBros\Jetpack\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

interface OwnerInterface
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function ownables(): Relation;

    /**
     * @param \ZablockiBros\Jetpack\Contracts\OwnableInterface $model
     *
     * @return bool
     */
    public function owns(OwnableInterface $model): bool;

    /**
     * @param \ZablockiBros\Jetpack\Contracts\OwnableInterface $model
     *
     * @return mixed
     */
    public function makeOwner(OwnableInterface $model);
}
