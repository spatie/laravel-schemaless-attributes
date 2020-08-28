<?php

namespace Spatie\SchemalessAttributes;

use Illuminate\Support\Collection;

/**
 * @property array $schemalessAttributes
 *
 * @mixin Collection
 */
trait SchemalessAttributesTrait
{
    public function initializeSchemalessAttributesTrait()
    {
        foreach ($this->getSchemalessAttributes() as $attribute) {
            $this->casts[$attribute] = 'array';
        }
    }

    public function getSchemalessAttributes(): array
    {
        return isset($this->schemalessAttributes) ? $this->schemalessAttributes : [];
    }

    /**
     * @param $key
     * @return mixed|SchemalessAttributes
     */
    public function __get($key)
    {
        if (in_array($key, $this->getSchemalessAttributes())) {
            return SchemalessAttributes::createForModel($this, $key);
        }

        return parent::__get($key);
    }
}
