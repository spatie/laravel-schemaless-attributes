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
        if ($this->isJsonArray($value)) {
            return $value;
        }

        $json = json_encode($value);

        if (! is_array(json_decode($json, true))) {
            return null;
        }

        return $json;
    }

    protected function isJsonArray($value): bool
    {
        if (! is_string($value)) {
            return false;
        }

        $array = json_decode($value, true);

        if (! is_array($array)) {
            return false;
        }

        return $value === json_encode($array);
    }
}
