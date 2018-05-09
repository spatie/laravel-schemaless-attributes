<?php

namespace Spatie\SchemalessAttributes\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\SchemalessAttributes\SchemalessAttributes;

class TestModel extends Model
{
    public $timestamps = false;

    public $guarded = [];

    public $casts = [
        'schemaless_attributes' => 'array'
    ];

    public function getSchemalessAttributesAttribute(): SchemalessAttributes
    {
        return SchemalessAttributes::createForModel($this, 'schemaless_attributes');
    }

    public function scopeWithSchemalessAttributes(): Builder
    {
        return SchemalessAttributes::scopeWithSchemalessAttributes('schemaless_attributes');
    }

}
