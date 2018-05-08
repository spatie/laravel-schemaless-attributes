<?php

namespace Spatie\SchemalessAttributes;

use ArrayAccess;
use Illuminate\Database\Eloquent\Model;

class SchemalessAttributes implements ArrayAccess
{
    /** @var \Illuminate\Database\Eloquent\Model */
    protected $model;

    /** @var string */
    protected $sourceAttributeName;

    /** @var array */
    protected $schemalessAttributes = [];

    public static function createForModel(Model $model, string $sourceAttributeName): self
    {
        return new static($model, $sourceAttributeName);
    }

    public function __construct(Model $model, string $sourceAttributeName)
    {
        $this->model = $model;

        $this->sourceAttributeName = $sourceAttributeName;

        $this->schemalessAttributes = $this->getRawSchemalessAttributes();
    }

    public function __get(string $name)
    {
        return array_get($this->schemalessAttributes, $name);
    }

    public function __set(string $name, $value)
    {
        array_set($this->schemalessAttributes, $name , $value);

        $this->model->{$this->sourceAttributeName} = $this->schemalessAttributes;
    }

    public function forget(string $name): self
    {
        $this->model->{$this->sourceAttributeName} = array_except($this->schemalessAttributes, $name);

        return $this;
    }

    public function all(): array
    {
        return $this->getRawSchemalessAttributes();
    }

    protected function getRawSchemalessAttributes(): array
    {
        return json_decode($this->model->getAttributes()[$this->sourceAttributeName] ?? '{}', true);
    }

    public function offsetExists($offset)
    {
        return array_has($this->schemalessAttributes);
    }

    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->{$offset} = $value;
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        $this->forget($offset);
    }
}