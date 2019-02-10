<?php

/**
 * @param       $model
 * @param array $attributes
 * @param null  $amount
 * @param array ...$states
 *
 * @return mixed
 */
function create($model, array $attributes = [], $amount = null, ...$states)
{
    $make = factory($model, $amount);

    if (! empty($states)) {
        $make = $make->states($states);
    }

    return $make->create($attributes);
}

/**
 * Create a collection of models.
 *
 * @param string $model
 * @param array  $attributes
 * @param null   $amount
 *
 * @return mixed
 */
function make($model, array $attributes = [], $amount = null)
{
    return factory($model, $amount)->make($attributes);
}

/**
 * Create an array of raw attribute arrays.
 *
 * @param string $model
 * @param array  $attributes
 * @param null   $amount
 *
 * @return mixed
 */
function raw($model, array $attributes = [], $amount = null)
{
    return factory($model, $amount)->raw($attributes);
}
