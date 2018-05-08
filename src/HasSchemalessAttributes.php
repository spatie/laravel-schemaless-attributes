<?php

namespace Spatie\SchemalessAttributes;

use Illuminate\Database\Eloquent\Builder;

trait HasSchemalessAttributes
{
    public function getCasts(): array
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

    public function addSchemalessAttributes(array $attributes): self
    {
        foreach ($attributes as $name => $value) {
            $this->schemaless_attributes->$name = $value;
        }

        $this->save();

        $this->refresh();

        return $this;
    }

    public function scopeWithSchemalessAttribute(): Builder
    {
        $arguments = func_get_args();

        unset($arguments[0]);

        return $this->withSchemalessAttributes(...$arguments);
    }

    public function scopeWithSchemalessAttributes(): Builder
    {
        $arguments = func_get_args();

        if (count($arguments) === 1) {
            [$builder] = $arguments;
            $schemalessAttributes = [];
        }

        if (count($arguments) === 2) {
            [$builder, $schemalessAttributes] = $arguments;
        }

        if (count($arguments) >= 3) {
            [$builder, $name, $value] = $arguments;
            $schemalessAttributes = [$name => $value];
        }

        foreach ($schemalessAttributes as $name => $value) {
            $builder->where("schemaless_attributes->{$name}", $value);
        }

        return $builder;
    }
}
