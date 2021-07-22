<?php

namespace Spatie\SchemalessAttributes\Tests;

use Illuminate\Database\Eloquent\Model;
use Spatie\SchemalessAttributes\Tests\Concerns\HasSchemalessAttributes;

class TestModelUsedHasSchemalessAttributesTrait extends Model
{
    use HasSchemalessAttributes;

    public $timestamps = false;

    public $guarded = [];
}
