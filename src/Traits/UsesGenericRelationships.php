<?php

namespace ZablockiBros\Jetpack\Traits;

use ZablockiBros\Jetpack\Models\Item;

trait UsesGenericRelationships
{
    /**
     * @param      $related
     * @param null $foreignKey
     * @param null $ownerKey
     * @param null $relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function belongsTo($related, $foreignKey = null, $ownerKey = null, $relation = null)
    {
        return $this->itemable($related);
    }

    /**
     * @param      $related
     * @param null $foreignKey
     * @param null $localKey
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function hasOne($related, $foreignKey = null, $localKey = null)
    {
        return $this->item($related);
    }

    /**
     * @param      $related
     * @param null $foreignKey
     * @param null $localKey
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function hasMany($related, $foreignKey = null, $localKey = null)
    {
        return $this->items($related);
    }

    /**
     * @param      $related
     * @param null $table
     * @param null $foreignPivotKey
     * @param null $relatedPivotKey
     * @param null $parentKey
     * @param null $relatedKey
     * @param null $relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function belongsToMany($related, $table = null, $foreignPivotKey = null, $relatedPivotKey = null,
        $parentKey = null, $relatedKey = null, $relation = null)
    {
        return $this->manyItems($related);
    }

    /**
     * @param string $class
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function itemable(string $class = Item::class)
    {
        return $this->morphTo('itemable')
            ->where('itemable_type', $class);
    }

    /**
     * @param string $class
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function item(string $class = Item::class)
    {
        return $this->morphOne(Item::class, 'itemable');
    }

    /**
     * @param null|string $type
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function items(string $class = Item::class)
    {
        return $this->morphMany(Item::class, 'itemable');
    }

    /**
     * @param null|string $type
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function manyItems(string $class = Item::class)
    {
        return $this->morphToMany(
            $class,
            'child',
            'item_items',
            'child_id',
            'parent_id'
        )
            ->withPivot('parent_type', 'parent_id', 'child_type', 'child_id')
            ->wherePivot('parent_type', get_class($this))
            ->withPivotValue('parent_type', get_class($this));
    }

    /**
     * @param string $class
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function byManyItems(string $class = Item::class)
    {
        return $this->morphedByMany(
            $class,
            'parent',
            'item_items',
            'parent_id',
            'child_id'
        )
            ->withPivot('parent_type', 'parent_id', 'child_type', 'child_id')
            ->wherePivot('child_type', get_class($this))
            ->withPivotValue('child_type', get_class($this));
    }
}
