<?php

namespace Spatie\SchemalessAttributes;

use Countable;
use ArrayAccess;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
        return $this->get($name);
    }

    public function get(string $name)
    {
        return array_get($this->schemalessAttributes, $name);
    }

    public function __set(string $name, $value)
    {
        $this->set($name, $value);
    }

    public function set(string $name, $value)
    {
        array_set($this->schemalessAttributes, $name, $value);

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

    public static function scopeWithSchemalessAttributes(string $attributeName): Builder
    {
        $arguments = debug_backtrace()[1]['args'];

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
            $builder->where("{$attributeName}->{$name}", $value);
        }

        return $builder;
    }
}
