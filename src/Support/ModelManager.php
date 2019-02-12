<?php

namespace ZablockiBros\Jetpack\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ModelManager
{
    /**
     * @var array
     */
    const CAST_TYPES = [
        'string',
        'int',
        'float',
        'decimal',
        'bool',
        'object',
        'array',
        'json',
        'date',
        'datetime',
        'timestamp',
    ];

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * ModelManager constructor.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return \ZablockiBros\Jetpack\Support\ModelManager
     */
    public static function load(Model $model)
    {
        return new self($model);
    }

    /**
     * @return array
     */
    public function indexValidationRules(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function storeValidationRules(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function updateValidationRules(): array
    {
        return [];
    }

    /**
     * @param $table
     *
     * @return mixed
     */
    public static function getTableColumns(string $table)
    {
        return DB::getSchemaBuilder()
            ->getColumnListing($table);
    }
}
