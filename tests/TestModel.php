<?php

namespace Spatie\SchemalessAttributes\Tests;

use Illuminate\Database\Eloquent\Model;
use Spatie\SchemalessAttributes\HasSchemalessAttributes;

class TestModel extends Model
{
    use HasSchemalessAttributes;

    public $casts = ['schemaless_attributes' => 'array'];

    public $timestamps = false;

    public $guarded = [];
}