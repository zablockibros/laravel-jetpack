<?php

namespace ZablockiBros\Jetpack\Models\Actions;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseAction
 *
 * @method static Action view()
 * @method static Action create()
 * @method static Action update()
 * @method static Action delete()
 */
abstract class BaseAction
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var callable
     */
    public $authorizeCallback;

    /**
     * @var bool
     */
    public $validates = true;

    /**
     * BaseAction constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return static
     */
    public static function __callStatic($name, $arguments)
    {
        return new static($name);
    }
}
