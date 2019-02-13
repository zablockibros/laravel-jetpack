<?php

namespace ZablockiBros\Jetpack\Traits;

use Illuminate\Database\Eloquent\Model;
use ZablockiBros\Jetpack\Scopes\ItemTypeScope;

trait UsesGenericTable
{
    /**
     * @return void
     */
    public static function bootUsesGenericTable()
    {
        static::creating(function (Model &$model) {
            $model->type = $model->type ?: get_class($model);
        });

        // limit query of this model to its type
        static::addGlobalScope(new ItemTypeScope());

        parent::boot();

        return;
    }

    /**
     * @return void
     */
    public function initializeUsesGenericTable()
    {
        $this->setTable('items');
    }

    /**
     * @return string
     */
    public function getSingularTypeName(): string
    {
        $reflect = new \ReflectionClass($this);

        return str_singular(snake_case($reflect->getShortName()));
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return 'items';
    }

    /**
     * Get the fillable attributes for the model.
     *
     * @return array
     */
    public function getFillable()
    {
        return array_merge(
            parent::getFillable(),
            [
                'itemable_type',
                'itemable_id',
                'item_type',
            ]
        );
    }
}
