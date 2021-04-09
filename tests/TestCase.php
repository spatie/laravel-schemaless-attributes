<?php

namespace Spatie\SchemalessAttributes\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\SchemalessAttributes\SchemalessAttributesServiceProvider;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    protected function getPackageProviders($app)
    {
        return [
            SchemalessAttributesServiceProvider::class,
        ];
    }

    /*
     * uses different table names for each test class to support 
     * running tests in parallel.
     */
    protected function setUpDatabase()
    {
        $parts = explode('\\', static::class);
        $class = array_pop($parts);
        
        Schema::dropIfExists("test_models_{$class}");

        Schema::create("test_models_{$class}", function (Blueprint $table) {
            $table->increments('id');
            $table->schemalessAttributes();
        });
    }
}
