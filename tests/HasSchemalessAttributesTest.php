<?php

namespace Spatie\SchemalessAttributes\Tests;

class HasSchemalessAttributesTest extends TestCase
{
    /** @var \Spatie\SchemalessAttributes\Tests\TestModel */
    protected $testModel;

    public function setUp()
    {
        parent::setUp();

        $this->testModel = TestModel::first();
    }

    /** @test */
    public function getting_a_non_existing_attribute_returs_null()
    {
        $this->assertNull($this->testModel->schemalessAttributes->non_existing);
    }
}