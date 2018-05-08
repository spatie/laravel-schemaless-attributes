<?php

namespace Spatie\SchemalessAttributes\Tests;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    protected function getPackageProviders($app)
    {
        return [
            \Spatie\SchemalessAttributes\SchemalessAttributesServiceProvider::class,
        ];
    }

    protected function setUpDatabase()
    {
        Schema::dropIfExists('test_models');

        Schema::create('test_models', function (Blueprint $table) {
            $table->increments('id');
            $table->schemalessAttributes();
        });
    }
}
