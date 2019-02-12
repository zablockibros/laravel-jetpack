<?php

namespace ZablockiBros\Jetpack\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasOwner
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model', 'App\Models\User'), 'owner_id');
    }

    /**
     * @return bool
     */
    public function hasOwner(): bool
    {
        return $this->owner()->exists();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return bool
     */
    public function isOwnedBy(Model $model): bool
    {
        return optional($this->owner()->first(['id']))->id === $model->id;
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
}
