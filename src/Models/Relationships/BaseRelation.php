<?php

namespace ZablockiBros\Jetpack\Models\Relationships;

/**
 * Class BaseRelation
 *
 * @method static Relation belongsTo(string $class)
 * @method static Relation hasOne(string $class)
 * @method static Relation hasMany(string $class)
 * @method static Relation belongsToMany(string $class)
 */
abstract class BaseRelation
{
    /**
     * @var string
     */
    public $baseModel;

    /**
     * @var string
     */
    public $relation;

    /**
     * @param $name
     * @param $arguments
     *
     * @return static
     */
    public static function __callStatic($name, $arguments)
    {
        $baseModelClass = ! empty($arguments[0]) ? $arguments[0] : null;

        if (! $baseModelClass) {
            throw new \Exception('Missing base model class in relation');
        }

        return new static($name, $baseModelClass);
    }

    /**
     * BaseRelation constructor.
     *
     * @param string $name
     * @param string $baseModelClass
     */
    public function __construct(string $name, string $baseModelClass)
    {
        $this->relation  = $name;
        $this->baseModel = $baseModelClass;
    }
}
