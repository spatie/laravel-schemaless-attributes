<?php

namespace Spatie\SchemalessAttributes;

use Illuminate\Database\Eloquent\Model;

class SchemalessAttributes
{
    /** @var \Illuminate\Database\Eloquent\Model */
    protected $model;

    /** @var string */
    protected $sourceAttributeName;

    /** @var array */
    protected $schemaless_attributes = [];

    public static function createForModel(Model $model, string $sourceAttributeName): self
    {
        return new static($model, $sourceAttributeName);
    }

    public function __construct(Model $model, string $sourceAttributeName)
    {
        $this->model = $model;

        $this->sourceAttributeName = $sourceAttributeName;

        $this->schemaless_attributes = $this->getRawSchemalessAttributes();
    }

    public function __get(string $name)
    {
        return array_get($this->schemaless_attributes, $name);
    }

    public function __set(string $name, $value)
    {
        $schemalessAttributes = $this->model->attributes[$this->sourceAttributeName];

        array_set($schemalessAttributes, $name , $value);

        $this->model->{$this->sourceAttributeName} = $schemalessAttributes;
    }

    protected function getRawSchemalessAttributes(): array
    {
        return json_decode($this->model->getAttributes()[$this->sourceAttributeName] ?? '{}', true);
    }
}