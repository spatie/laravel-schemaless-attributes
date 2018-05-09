<?php

namespace Spatie\SchemalessAttributes\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Spatie\SchemalessAttributes\HasSchemalessAttributes;

class TestModel extends Model
{
    use HasSchemalessAttributes;

    public $timestamps = false;

    public $guarded = [];

    public $casts = [
        'schemaless_attributes' => 'array'
    ];

}
