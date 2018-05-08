<?php

namespace Spatie\SchemalessAttributes;

trait HasSchemalessAttributes
{
    public function getCasts() : array
    {
        return array_merge(
            parent::getCasts(),
            ['schemaless_attributes' => 'array']
        );
    }

    public function getSchemalessAttributesAttribute(): SchemalessAttributes
    {
        return SchemalessAttributes::createForModel($this, 'schemaless_attributes');
    }
}