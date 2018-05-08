<?php

namespace Spatie\SchemalessAttributes;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class SchemalessAttributesServiceProvider extends ServiceProvider
{
    public function register()
    {
        Blueprint::macro('schemalessAttributes', function (string $columnName = 'schemaless_attributes') {
            $this->json($columnName)->nullable();
        });
    }
}
