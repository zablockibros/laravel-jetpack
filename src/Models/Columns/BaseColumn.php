<?php

namespace ZablockiBros\Jetpack\Models\Columns;

use Illuminate\Http\Request;

/**
 * Class BaseColumn
 *
 * @package ZablockiBros\Jetpack\Models\Columns
 *
 * @method static Column string(string $name, ?int $length = null)
 * @method static Column text(string $name)
 * @method static Column longText(string $name)
 * @method static Column integer(string $name)
 * @method static Column tinyInteger(string $name, $autoIncrement = false, $unsigned = false)
 * @method static Column unsignedInteger(string $name, $autoIncrement = false)
 * @method static Column float($column, $total = 8, $places = 2)
 * @method static Column double($column, $total = null, $places = null)
 * @method static Column decimal($column, $total = 8, $places = 2)
 * @method static Column boolean($column)
 * @method static Column json($column)
 * @method static Column date($column)
 * @method static Column dateTime($column, $precision = 0)
 * @method static Column dateTimeTz($column, $precision = 0)
 * @method static Column time($column, $precision = 0)
 * @method static Column timeTz($column, $precision = 0)
 * @method static Column timestamp($column, $precision = 0)
 * @method static Column timestampTz($column, $precision = 0)
 */
abstract class BaseColumn
{
    /**
     * @var string
     */
    public $type = 'string';

    /**
     * @var null|string
     */
    public $name = null;

    /**
     * @var \ZablockiBros\Jetpack\Models\Columns\ColumnDefinition
     */
    public $defintion;

    /**
     * @var array
     */
    public $parameters = [];

    /**
     * @var array
     */
    public $rules = [];

    /**
     * @var array
     */
    public $creationRules = [];

    /**
     * @var array
     */
    public $updateRules = [];

    /**
     * BaseColumn constructor.
     *
     * @param string $type
     * @param string $name
     * @param array  $parameters
     */
    public function __construct(string $type, string $name, array $parameters)
    {
        $this->type   = $type;
        $this->name   = $name;
        $this->defintion = new ColumnDefinition([]);
    }

    /**
     * @param       $method
     * @param array ...$args
     *
     * @return static
     * @throws \Exception
     */
    public static function __callStatic($method, ...$args)
    {
        $args = is_array($args) ? $args : [];
        reset($args);
        $name = array_shift($args);

        if (empty($name)) {
            throw new \Exception("Missing column name");
        }

        return new static($method, $name, $args);
    }

    /**
     * @param $method
     * @param $args
     *
     * @return \ZablockiBros\Jetpack\Models\Columns\ColumnDefinition
     */
    public function __call($method, $args)
    {
        if (! $this->defintion) {
            return $this->createDefinition();
        }

        return $this->defintion;
    }

    /**
     * @param  string  $type
     * @param  string  $name
     * @param  array  $parameters
     * @return \ZablockiBros\Jetpack\Models\Columns\ColumnDefinition
     */
    protected function createDefinition()
    {
        $this->defintion = $column = new ColumnDefinition(
            array_merge([
                'name' => $this->name,
                'type' => $this->type,
            ], $this->parameters)
        );

        return $column;
    }

    /**
     * Set the validation rules for the field.
     *
     * @param callable|array|string $rules
     *
     * @return $this
     */
    protected function parseRules($rules)
    {
        return is_string($rules) ? func_get_args() : $rules;
    }

    /**
     * @param                          $rules
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function returnRulesForRequest($rules, Request $request)
    {
        return [
            $this->name => is_callable($rules)
                ? call_user_func($rules, $request)
                : $rules,
        ];
    }
}
