<?php

namespace ZablockiBros\Jetpack\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Field extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    const FIELDSET_TYPES = [
        'string',
        'int',
        'bool',
        'float',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'fieldable_type',
        'fieldable_id',
        'columns',
        'name',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'columns' => 'array',
    ];

    /**
     * @return void
     */
    public static function boot()
    {
        static::saving(function (Field $model) {
            // saving
        });

        parent::boot();

        return;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function fieldable()
    {
        return $this->morphTo('fieldable');
    }

    /**
     * @return array
     */
    public function getColumnsAttribute($value)
    {
        $value = $value ?: [];

        return is_array($value)
            ? $value
            : json_decode($value, true);
    }

    /**
     * @param $value
     */
    public function setColumnsAttribute($value)
    {
        $value = is_array($value) ? $value : [];

        $this->attributes['columns'] = json_encode($value);

        return;
    }

    /**
     * @param $key
     */
    public function __get($key)
    {
        $value = $this->getAttribute($key);

        if (isset($value)) {
            return $value;
        }

        return $this->getColumn($key);
    }

    /**
     * @param  string  $key
     * @param  mixed  $value
     *
     * @return void
     */
    public function __set($key, $value)
    {
        if (
            array_key_exists($key, $this->attributes)
            || $this->hasSetMutator($key)
            || $this->isDateAttribute($key)
            || $this->isJsonCastable($key)
        ) {
            $this->setAttribute($key, $value);

            return;
        }

        $this->setColumn($key, $value);

        return;
    }

    /**
     * @param string $key
     *
     * @return null
     */
    public function getColumn(string $key)
    {
        $value = $this->columns[$key] ?? null;

        if (isset($value) && is_array($value)) {
            return (object) $value;
        }

        return $value;
    }

    /**
     * @param string $key
     * @param        $value
     */
    public function setColumn(string $key, $value)
    {
        $columns = $this->columns ?: [];

        if (is_object($value)) {
            $value = json_decode(json_encode($value), true);
        }

        $castType      = $this->getParentCastType($key);
        $columns[$key] = ! is_null($castType)
            ? $this->castAttribute($castType, $value)
            : $value;

        $this->columns = $columns;

        return;
    }

    /**
     * Gets the column from associated model if it exists
     *
     * @param string $key
     *
     * @return null|string
     */
    public function getParentCastType(string $key): ?string
    {
        $casts = optional($this->fieldable)
            ->getCasts() ?? [];

        return $casts[$key] ?? null;
    }
}
