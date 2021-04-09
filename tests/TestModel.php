<?php

namespace Spatie\SchemalessAttributes\Tests;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;

class TestModel extends Model
{
    public $timestamps = false;

    public $guarded = [];

    public $casts = [
        'schemaless_attributes' => SchemalessAttributes::class,
    ];

    public function scopeWithSchemalessAttributes(): Builder
    {
        return $this->schemaless_attributes->modelScope();
    }
}
