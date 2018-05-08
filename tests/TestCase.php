<?php

namespace Spatie\SchemalessAttributes\Tests;
use Dotenv\Dotenv;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    use RefreshDatabase;

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
        $this->app['db']->connection()->getSchemaBuilder()->create('test_models', function (Blueprint $table) {
            $table->increments('id');
            $table->schemalessAttributes();
        });

        TestModel::create();
    }
}
