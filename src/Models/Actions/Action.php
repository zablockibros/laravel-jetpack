<?php

namespace ZablockiBros\Jetpack\Models\Actions;

/**
 * Class Action
 *
 * @inheritdoc
 */
class Action extends BaseAction
{
    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @param callable $callback
     *
     * @return $this
     */
    public function authorize(callable $callback)
    {
        $this->authorizeCallback = $callback;

        return $this;
    }

    /**
     * @param bool $validates
     *
     * @return $this
     */
    public function validate(bool $validates = true)
    {
        $this->validates = $validates;

        return $this;
    }

    /**
     * @return string
     */
    public function authorizeCallback(): ?callable
    {
        return $this->authorizeCallback;
    }
}
