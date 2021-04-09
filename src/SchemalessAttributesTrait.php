<?php

namespace Spatie\SchemalessAttributes;

use Illuminate\Support\Collection;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes as SchemalessAttributesCast;

/**
 * @property array $schemalessAttributes
 *
 * @mixin Collection
 */
trait SchemalessAttributesTrait
{
    public function initializeSchemalessAttributesTrait(): void
    {
        foreach ($this->getSchemalessAttributes() as $attribute) {
            $this->casts[$attribute] = SchemalessAttributesCast::class;
        }
    }

    public function getSchemalessAttributes(): array
    {
        return $this->schemalessAttributes ?? [];
    }

    /**
     * @param $key
     * @return mixed|SchemalessAttributes
     */
    public function __get($key)
    {
        if (in_array($key, $this->getSchemalessAttributes(), true)) {
            return SchemalessAttributes::createForModel($this, $key);
        }

        return parent::__get($key);
    }
}
