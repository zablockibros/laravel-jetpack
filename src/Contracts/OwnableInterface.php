<?php

namespace ZablockiBros\Jetpack\Contracts;

use Illuminate\Database\Eloquent\Relations\Relation;

interface OwnableInterface
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function owner(): Relation;

    /**
     * @return bool
     */
    public function hasOwner(): bool;

    /**
     * @param \ZablockiBros\Jetpack\Contracts\OwnerInterface $model
     *
     * @return bool
     */
    public function isOwnedBy(OwnerInterface $model): bool;

    /**
     * @param null|\ZablockiBros\Jetpack\Contracts\OwnableInterface $model
     *
     * @return mixed
     */
    public function setOwner(?OwnableInterface $model);
}
