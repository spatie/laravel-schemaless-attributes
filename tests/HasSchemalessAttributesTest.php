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
    public function getting_a_non_existing_schemaless_attribute_returns_null()
    {
        $this->assertNull($this->testModel->schemaless_attributes->non_existing);
    }

    /** @test */
    public function an_schemaless_attribute_can_be_set()
    {
        $this->testModel->schemaless_attributes->name = 'value';

        $this->assertEquals('value', $this->testModel->schemaless_attributes->name);
    }

    /** @test */
    public function schemaless_attributes_will_get_saved_with_the_model()
    {
        $this->testModel->schemaless_attributes->name = 'value';

        $this->testModel->save();

        $this->assertEquals('value', $this->testModel->fresh()->schemaless_attributes->name);
    }

    /** @test */
    public function it_can_handle_an_array()
    {
        $array = [
            'one' => 'value',
            'two' => 'another value',
        ];

        $this->testModel->schemaless_attributes->array = $array;

        $this->assertEquals($array, $this->testModel->schemaless_attributes->array);
    }

    /** @test */
    public function it_can_forget_a_single_schemaless_attribute()
    {
        $this->testModel->schemaless_attributes->name = 'value';

        $this->assertEquals('value', $this->testModel->schemaless_attributes->name);

        $this->testModel->schemaless_attributes->forget('name');

        $this->assertNull($this->testModel->schemaless_attributes->name);
    }

    /** @test */
    public function it_can_forget_a_schemaless_attribute_using_dot_notation()
    {
        $this->testModel->schemaless_attributes->member = ['name' => 'John', 'age' => 30];

        $this->testModel->schemaless_attributes->forget('member.age');

        $this->assertEquals($this->testModel->schemaless_attributes->member, ['name' => 'John']);
    }
}