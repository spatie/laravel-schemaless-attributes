<?php

namespace Spatie\SchemalessAttributes\Tests;

use Illuminate\Database\Eloquent\Model;
use Spatie\SchemalessAttributes\SchemalessAttributesTrait;

class TestModelUsedTrait extends Model
{
    use SchemalessAttributesTrait;

    public $incrementing = false;

    public $schemalessAttributes = [
        'schemaless_attributes',
        'other_schemaless_attributes'
    ];
}
