<?php

namespace ZablockiBros\Jetpack\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use ZablockiBros\Jetpack\Traits\UsesGenericFields;
use ZablockiBros\Jetpack\Traits\UsesGenericRelationships;
use ZablockiBros\Jetpack\Traits\UsesGenericTable;

class Item extends Model
{
    use UsesGenericTable;
    use UsesGenericRelationships;
    use UsesGenericFields;
    use SoftDeletes;
}
