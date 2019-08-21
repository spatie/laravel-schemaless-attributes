<?php

namespace Spatie\SchemalessAttributes;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SchemalessAttributes extends Collection
{
    /** @var \Illuminate\Database\Eloquent\Model */
    protected $model;

    /** @var string */
    protected $sourceAttributeName;

    public static function createForModel(Model $model, string $sourceAttributeName): self
    {
        return new static($model, $sourceAttributeName);
    }

    public function __construct(Model $model, string $sourceAttributeName)
    {
        $this->model = $model;

        $this->sourceAttributeName = $sourceAttributeName;

        parent::__construct($this->getRawSchemalessAttributes());
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __set(string $name, $value)
    {
        $this->put($name, $value);
    }

    public function set($key, $value = null)
    {
        if(is_iterable($key)) {
            return $this->merge($key);
        }

        return $this->put($key, $value);
    }

    public function get($key, $default = null)
    {
        return data_get($this->items, $key, $default);
    }

    public function put($key, $value)
    {
        return $this->override(parent::put($key, $value));
    }

    public function merge($items)
    {
        return $this->override(array_merge($this->items, $this->getArrayableItems($items)));
    }

    public function forget($keys)
    {
        return $this->override(parent::forget($keys));
    }

    public function offsetGet($key)
    {
        return $this->get($key);
    }

    public function offsetSet($key, $value)
    {
        return $this->override(data_set($this->items, $key, $value));
    }

    public function offsetUnset($offset)
    {
        $this->override(Arr::except($this->items, $offset));
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

    protected function getRawSchemalessAttributes(): array
    {
        return $this->model->fromJson($this->model->getAttributes()[$this->sourceAttributeName] ?? '{}');
    }

    protected function override(iterable $collection)
    {
        $this->items = $this->getArrayableItems($collection);
        $this->model->{$this->sourceAttributeName} = $this->items;

        return $this;
    }
}
