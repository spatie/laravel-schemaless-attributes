<?php

namespace Spatie\SchemalessAttributes;

use Illuminate\Database\Schema\Blueprint;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SchemalessAttributesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-schemaless-attributes');
    }

    public function registeringPackage(): void
    {
        Blueprint::macro('schemalessAttributes', function (string $columnName = 'schemaless_attributes', string $columnType = 'json') {
            
            if ($columnType === 'jsonb') {
                return $this->jsonb($columnName)->nullable();                
            }
            
            return $this->json($columnName)->nullable();
        });
    }
}
