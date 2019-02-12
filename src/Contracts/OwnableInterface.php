<?php

namespace ZablockiBros\Jetpack\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

interface OwnableInterface
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function owner(): Relation;

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getOwner(): Model;

    /**
     * @return bool
     */
    public function hasOwner(): bool;

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return bool
     */
    public function isOwnedBy(Model $model): bool;

    /**
     * @param \Illuminate\Database\Eloquent\Model|null $model
     *
     * @return mixed
     */
    public function setOwner(?Model $model);

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model   $owner
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereOwnedBy(Builder $query, Model $owner);

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model   $owner
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereNotOwnedBy(Builder $query, Model $owner);
}
