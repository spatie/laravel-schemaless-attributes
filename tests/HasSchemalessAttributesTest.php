<?php

namespace Spatie\SchemalessAttributes\Tests;

class HasSchemalessAttributesTest extends TestCase
{
    /** @var \Spatie\SchemalessAttributes\Tests\TestModel */
    protected $testModel;

    public function setUp()
    {
        parent::setUp();

        $this->testModel = TestModel::create();
    }

    /** @test */
    public function getting_a_non_existing_attribute_returns_null()
    {
        $this->assertNull($this->testModel->schemaless_attributes->non_existing);
    }

    /** @test */
    public function an_attribute_can_be_set()
    {
        $this->testModel->schemaless_attributes->name = 'value';

        $this->assertEquals('value', $this->testModel->schemaless_attributes->name);
    }
}