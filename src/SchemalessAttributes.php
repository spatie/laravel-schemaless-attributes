<?php

namespace Spatie\SchemalessAttributes;

use Countable;
use ArrayAccess;
use JsonSerializable;
use IteratorAggregate;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @mixin Collection
 */
class SchemalessAttributes implements ArrayAccess, Arrayable, Countable, IteratorAggregate, Jsonable, JsonSerializable
{
    /** @var \Illuminate\Database\Eloquent\Model */
    protected $model;

    /** @var string */
    protected $sourceAttributeName;

    /** @var Collection */
    protected $collection;

    public static function createForModel(Model $model, string $sourceAttributeName): self
    {
        return new static($model, $sourceAttributeName);
    }

    public function __construct(Model $model, string $sourceAttributeName)
    {
        $this->model = $model;

        $this->sourceAttributeName = $sourceAttributeName;

        $this->collection = new Collection($this->getRawSchemalessAttributes());
    }

    public function __call($name, $arguments)
    {
        $result = call_user_func_array([$this->collection, $name], $arguments);

        $this->override($this->collection->toArray());

        return $result;
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    /**
     * @see Collection::get()
     *
     * @param $key
     * @param null $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return data_get($this->collection, $key, $default);
    }

    /**
     * @see Collection::set()
     *
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function set($key, $value = null)
    {
        if (is_iterable($key)) {
            return $this->override($this->collection->merge($key));
        }

        $items = $this->collection->toArray();

        return $this->override(data_set($items, $key, $value));
    }

    /**
     * @see Collection::forget()
     *
     * @param $keys
     *
     * @return SchemalessAttributes
     */
    public function forget($keys)
    {
        $items = $this->collection->toArray();

        foreach ((array) $keys as $key) {
            Arr::forget($items, $key);
        }

        return $this->override($items);
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

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetExists($offset)
    {
        return $this->collection->offsetExists($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        $this->forget($offset);
    }

    public function toArray()
    {
        return $this->collection->toArray();
    }

    public function toJson($options = 0)
    {
        return $this->collection->toJson($options);
    }

    public function jsonSerialize()
    {
        return $this->collection->jsonSerialize();
    }

    public function count()
    {
        return $this->collection->count();
    }

    public function getIterator()
    {
        return $this->collection->getIterator();
    }

    protected function getRawSchemalessAttributes(): array
    {
        return $this->model->fromJson($this->model->getAttributes()[$this->sourceAttributeName] ?? '{}');
    }

    protected function override(iterable $collection)
    {
        $this->collection = new Collection($collection);
        $this->model->{$this->sourceAttributeName} = $this->collection->toArray();

        return $this;
    }
}
