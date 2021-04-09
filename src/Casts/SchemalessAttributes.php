<?php

namespace Spatie\SchemalessAttributes\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Spatie\SchemalessAttributes\SchemalessAttributes as BaseSchemalessAttributes;

class SchemalessAttributes implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return \Spatie\SchemalessAttributes\SchemalessAttributes
     */
    public function get($model, $key, $value, $attributes)
    {
        return new BaseSchemalessAttributes($model, $key);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string $key
     * @param  \Spatie\SchemalessAttributes\SchemalessAttributes $value
     * @param  array $attributes
     * @return mixed
     */
    public function set($model, $key, $value, $attributes)
    {
        return json_encode($value);
    }
}
