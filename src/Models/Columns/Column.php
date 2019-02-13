<?php

namespace ZablockiBros\Jetpack\Models\Columns;

use Illuminate\Http\Request;

/**
 * Class Column
 *
 * @inheritdoc
 */
class Column extends BaseColumn
{
    /**
     * @return null|string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function parameters()
    {
        return $this->parameters;
    }

    /**
     * @return \ZablockiBros\Jetpack\Models\Columns\ColumnDefinition
     */
    public function definition(): ColumnDefinition
    {
        return $this->defintion;
    }

    /**
     * Set the validation rules for the field.
     *
     * @param callable|array|string $rules
     *
     * @return $this
     */
    public function rules($rules)
    {
        $this->rules = $this->parseRules($rules);

        return $this;
    }

    /**
     * @param callable|array|string $rules
     *
     * @return $this
     */
    public function creationRules($rules)
    {
        $this->creationRules = $this->parseRules($rules);

        return $this;
    }

    /**
     * @param callable|array|string $rules
     *
     * @return $this
     */
    public function updateRules($rules)
    {
        $this->updateRules = $this->parseRules($rules);

        return $this;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function getRules(Request $request)
    {
        return $this->returnRulesForRequest($this->rules, $request);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function getCreationRules(Request $request)
    {
        return $this->returnRulesForRequest($this->creationRules, $request);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function getUpdateRules(Request $request)
    {
        return $this->returnRulesForRequest($this->updateRules, $request);
    }
}
