<?php

namespace Spatie\SchemalessAttributes;

use Illuminate\Database\Eloquent\Model;

class SchemalessAttributes
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
    }

    public function __get(string $name)
    {
        return array_get($this->model->{$this->sourceAttributeName}, $name);
    }
}