<?php

namespace ZablockiBros\Jetpack\Traits;

use Illuminate\Database\Eloquent\Model;

trait OwnsGenericFields
{
    /**
     * @return void
     */
    public static function bootOwnsGenericFields()
    {
        static::creating(function (Model $model) {
            //
        });

        return;
    }

    /**
     * @return void
     */
    public function initializeOwnsGenericFields()
    {
        //
    }

    /**
     * @param $value
     *
     * @return array
     */
    public function getFieldsAttribute($value)
    {
        return $value ?? [];
    }

    /**
     * @return array
     */
    public function getCasts()
    {
        return array_merge(
            parent::getCasts(),
            [
                'fields' => 'array',
            ]
        );
    }

    /**
     * @param $key
     */
    public function getAttribute($key)
    {
        $return = parent::getAttribute($key);

        if (! empty($return)) {
            return $return;
        }

        if (array_key_exists($key, $this->fields)) {
            $value = $this->fields[$key];
        }

        if ($this->hasCast($key)) {
            return $this->castAttribute($key, $value);
        }

        return $return;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        parent::setAttribute($key, $value);

        // if it's in the original then set it on fields
        if (array_key_exists($key, $this->initialAttributes())) {
            $this->fields[$key] = $value;
        }

        return $this;
    }

    /**
     * Get the attributes to be saved
     *
     * @return array
     */
    public function getAttributes()
    {
        return collect(parent::getAttributes())
            ->except(array_keys($this->fieldAttributes()))
            ->toArray();
    }

    /**
     * @param $key
     * @param $value
     */
    protected function setFieldsAttribute($key, $value)
    {
        $this->attributes['fields'] = array_merge(
            $this->fields, // uses accessor above
            [$key => $value]
        );
    }

    /**
     * @return array
     */
    protected function initialAttributes()
    {
        return $this->newInstance()->getOriginal() ?? [];
    }

    /**
     * @return array
     */
    protected function fieldAttributes()
    {
        return collect($this->attributes)
            ->except($this->nonFieldAttributes())
            ->toArray();
    }

    /**
     * @return array
     */
    protected function nonFieldAttributes()
    {
        $keys = [
            $this->getKeyName(),
            'deleted_at',
            'itemable_type',
            'itemable_id',
            'item_type',
        ];

        if ($this->usesTimestamps()) {
            $keys = array_merge(
                $keys,
                [
                    $this->getCreatedAtColumn(),
                    $this->getCreatedAtColumn(),
                ]
            );
        }

        return $keys;
    }
}
