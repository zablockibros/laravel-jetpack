<?php

namespace ZablockiBros\Jetpack\Models\Decorators;

use Illuminate\Database\Eloquent\Model;

abstract class InitializeDecorator extends Model
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return mixed
     */
    abstract public function initializeDecorator(Model $model);

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public static function decorate(Model $model)
    {
        return new static([], $model);
    }

    /**
     * TraitDecorator constructor.
     *
     * @param array ...$args
     */
    public function __construct(...$args)
    {
        reset($args);
        $this->model = $args[0];

        parent::__construct([]);

        $this->addDecorator();

        return $this;
    }

    /**
     * @param string[] ...$traits
     */
    public function addDecorator()
    {
        $class = get_class($this->model);

        $booted = [];

        parent::$traitInitializers[$class] = parent::$traitInitializers[$class] ?? [];

        $method = 'boot';

        if (method_exists($class, $method) && ! in_array($method, $booted)) {
            forward_static_call([$class, $method]);

            $booted[] = $method;
        }

        if (method_exists(static::class, $method = 'initializeDecorator')) {
            parent::$traitInitializers[$class][] = $method;

            parent::$traitInitializers[$class] = array_unique(
                parent::$traitInitializers[$class]
            );
        }
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return
     */
    protected function macroableClass(Model $model)
    {
        return new class($model) {
            /**
             * @var Model
             */
            protected static $model;

            /**
             *  constructor.
             *
             * @param array ...$args
             */
            public function __construct(...$args)
            {
                static::$model = $args[0];
            }

            /**
             * @param  string  $key
             * @return mixed
             */
            public function __get($key)
            {
                return static::$model->$key;
            }

            /**
             * @param  string  $key
             * @param  mixed  $value
             * @return void
             */
            public function __set($key, $value)
            {
                static::$model->$key = $value;
            }

            /**
             * @param       $method
             * @param array ...$arguments
             *
             * @return mixed
             */
            public function __call($method, ...$arguments)
            {
                return static::$model->{$method}(...$arguments);
            }

            /**
             * @param       $method
             * @param array ...$arguments
             *
             * @return mixed
             */
            public static function __callStatic($method, ...$arguments)
            {
                return static::$model::{$method}($arguments);
            }
        };
    }
}
