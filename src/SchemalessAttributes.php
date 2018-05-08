<?php

namespace Spatie\SchemalessAttributes;

use ArrayAccess;
use Countable;
use Illuminate\Database\Eloquent\Model;

class SchemalessAttributes implements ArrayAccess, Countable
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
        return array_has($this->schemalessAttributes, $offset);
    }

    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    public function offsetSet($offset, $value)
    {
        $this->{$offset} = $value;
    }

    public function offsetUnset($offset)
    {
        $this->forget($offset);
    }

    public function count()
    {
        return count($this->schemalessAttributes);
    }
}