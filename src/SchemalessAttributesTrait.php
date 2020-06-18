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
    /**
     * Register schemalessAttributes attributes as array(cast)
     */
    public function initializeSchemalessAttributesTrait()
    {
        $casts = [];

        foreach ($this->getSchemalessAttributes() as $attribute) {
            $casts[$attribute] = 'array';
        }

        $this->mergeCasts($casts);
    }

    /**
     * @return array
     */
    protected function getSchemalessAttributes()
    {
        return $this->schemalessAttributes ?? [];
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
