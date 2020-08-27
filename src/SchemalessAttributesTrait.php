<?php

namespace Spatie\SchemalessAttributes;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @property array $schemalessAttributes
 *
 * @mixin Collection
 */
trait SchemalessAttributesTrait
{
    /**
     * @var
     */
    private $initializeSchemalessAttributesTrait;

    /**
     * Get the casts array.
     *
     * @return array
     */
    public function getCasts()
    {
        if (Str::startsWith(app()->version(), '5.6')) {
            if (! $this->initializeSchemalessAttributesTrait) {
                $this->initializeSchemalessAttributesTrait();
                $this->initializeSchemalessAttributesTrait = true;
            }
        }
        return parent::getCasts();
    }

    public function initializeSchemalessAttributesTrait()
    {
        foreach ($this->getSchemalessAttributes() as $attribute) {
            $this->casts[$attribute] = 'array';
        }
    }

    /**
     * @return array
     */
    public function getSchemalessAttributes()
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
