<?php

namespace ZablockiBros\Jetpack\Support;

abstract class Chainable
{
    /**
     * @var mixed
     */
    protected $return;

    /**
     * @param $name
     * @param $arguments
     *
     * @return $this
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        if (! method_exists($this, $name)) {
            throw new \Exception("Missing method [$name] on class [${${get_class($this)}}]");
        }

        $this->return = $this->{$name}(...$arguments);

        return $this;
    }

    /**
     * Breaks fluent interface and returns last returned method
     *
     * @return mixed
     */
    public function return()
    {
        return $this->return;
    }
}
