<?php

namespace Spatie\SchemalessAttributes;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Schema\Blueprint;

class SchemalessAttributesServiceProvider extends ServiceProvider
{
    public function register()
    {
        Blueprint::macro('schemalessAttributes', function (string $columnName = 'schemaless_attributes') {
            return $this->json($columnName)->nullable();
        });
    }
}
