<?php

namespace Spatie\SchemalessAttributes;

trait HasSchemalessAttributes
{
    public function getSchemalessAttributesAttribute(): SchemalessAttributes
    {
        return SchemalessAttributes::createForModel($this, 'schemaless_attributes');
    }
}