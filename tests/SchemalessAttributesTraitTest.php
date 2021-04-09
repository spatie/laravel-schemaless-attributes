<?php

namespace Spatie\SchemalessAttributes\Tests;

use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;

class SchemalessAttributesTraitTest extends TestCase
{
    /** @var \Spatie\SchemalessAttributes\Tests\TestModelUsedTrait */
    protected $testModel;

    public function setUp(): void
    {
        parent::setUp();

        $this->testModel = new TestModelUsedTrait();
    }

    /** @test */
    public function schemaless_attributes_cast_as_array_initialize_schemaless_attributes_trait()
    {
        $this->assertSame(SchemalessAttributes::class, $this->testModel->getCasts()['schemaless_attributes']);
    }

    /** @test */
    public function other_schemaless_attributes_cast_as_array_initialize_schemaless_attributes_trait()
    {
        $this->assertSame(SchemalessAttributes::class, $this->testModel->getCasts()['other_schemaless_attributes']);
    }

    /** @test */
    public function test_get_schemaless_attributes()
    {
        $this->assertEquals(['schemaless_attributes', 'other_schemaless_attributes'], $this->testModel->getSchemalessAttributes());
    }

    /** @test */
    public function test_get()
    {
        $this->assertNull($this->testModel->schemaless_attributes->name);
    }

    /** @test */
    public function test_parent_get()
    {
        $this->assertNull($this->testModel->not_existing_schemaless);
    }
}
