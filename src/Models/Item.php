<?php

namespace ZablockiBros\Jetpack\Models;

use ZablockiBros\Jetpack\Traits\HasFields;
use ZablockiBros\Jetpack\Scopes\ItemTypeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFields;
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'items';

    /**
     * @var array
     */
    protected $fillable = [
        'itemable_type',
        'itemable_id',
        'type',
    ];

    /**
     * Item constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * @return void
     */
    public static function boot()
    {
        static::creating(function (&$model) {
            $model->type = $model->type ?: get_class($model);
        });

        // limit query of this model to its type
        //static::addGlobalScope(new ItemTypeScope());

        // todo: make relationship methods

        parent::boot();

        return;
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

    /**
     * @return string
     */
    public function getSingularTypeName(): string
    {
        $reflect = new \ReflectionClass($this);

        return str_singular(snake_case($reflect->getShortName()));
    }
}
