<?php

namespace ZablockiBros\Jetpack\Models;

use ZablockiBros\Jetpack\Models\Columns\Column;

abstract class BaseModel
{
    /**
     * @var string
     */
    protected $model;

    /**
     * @var bool
     */
    protected $validates = true;

    /**
     * @var bool
     */
    protected $authorizes = true;

    /**
     * @var bool
     */
    protected $relates = true;

    /**
     * @return array
     */
    public function columns()
    {
        return [
            //Column::string('')
            //    ->
        ];
    }

    /**
     * @return array
     */
    public function relations()
    {
        return [];
    }
}
