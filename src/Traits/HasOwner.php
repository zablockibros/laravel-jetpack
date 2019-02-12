<?php

namespace ZablockiBros\Jetpack\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use ZablockiBros\Jetpack\Contracts\OwnerInterface;

trait HasOwner
{
    /**
     * Boot the HasMorphOwner trait for a model.
     *
     * @return void
     */
    public static function bootHasOwner()
    {
        //static::observe(OwnableObserver::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function owner(): BelongsTo
    {
        return $this->morphTo('owned_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getOwner(): Model
    {
        return $this->owner;
    }

    /**
     * @return bool
     */
    public function hasOwner(): bool
    {
        return ! is_null($this->getOwner());
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return bool
     */
    public function isOwnedBy(Model $model): bool
    {
        if (!$this->hasOwner()) {
            return false;
        }

        return $model->getKey() === $this->getOwner()->getKey()
            && $model->getMorphClass() === $this->getOwner()->getMorphClass();
    }

    /**
     * @param null|\Illuminate\Database\Eloquent\Model $model
     */
    public function setOwner(?Model $model)
    {
        if (is_null($model)) {
            $this->owner()->dissociate();

            return;
        }

        $this->owner()->associate($model);

        return;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model   $owner
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereOwnedBy(Builder $query, Model $owner)
    {
        return $query->where([
            'owned_by_id' => $owner->getKey(),
            'owned_by_type' => $owner->getMorphClass(),
        ]);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model   $owner
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereNotOwnedBy(Builder $query, Model $owner)
    {
        return $query->where(function (Builder $q) use ($owner) {
            $q->where('owned_by_id', '!=', $owner->getKey())
                ->orWhere('owned_by_type', '!=', $owner->getMorphClass());
        });
    }
}
