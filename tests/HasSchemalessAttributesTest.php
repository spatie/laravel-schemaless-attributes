<?php

namespace Spatie\SchemalessAttributes\Tests;

use Illuminate\Support\Collection;

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

        $this->assertEquals('value', $this->testModel->schemaless_attributes->name);
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
    public function it_can_get_values_using_dot_notation()
    {
        $this->testModel->schemaless_attributes->rey = ['side' => 'light'];
        $this->testModel->schemaless_attributes->snoke = ['side' => 'dark'];

        $this->assertEquals('light', $this->testModel->schemaless_attributes->get('rey.side'));
    }

    /** @test */
    public function it_can_set_values_using_dot_notation()
    {
        $this->testModel->schemaless_attributes->rey = ['side' => 'light'];
        $this->testModel->schemaless_attributes->set('rey.side', 'dark');

        $this->assertEquals('dark', $this->testModel->schemaless_attributes->get('rey.side'));
    }

    /** @test */
    public function it_can_set_all_schemaless_attributes_at_once()
    {
        $array = [
            'rey' => ['side' => 'light'],
            'snoke' => ['side' => 'dark'],
        ];

        $this->testModel->schemaless_attributes = $array;

        $this->assertEquals($array, $this->testModel->schemaless_attributes->all());
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

    /** @test */
    public function it_can_get_all_schemaless_attributes()
    {
        $this->testModel->schemaless_attributes = ['name' => 'value'];

        $this->assertEquals(['name' => 'value'], $this->testModel->schemaless_attributes->all());
    }

    /** @test */
    public function it_will_use_the_correct_datatype()
    {
        $this->testModel->schemaless_attributes->bool = true;
        $this->testModel->schemaless_attributes->float = 12.34;

        $this->testModel->save();

        $this->testModel->refresh();

        $this->assertSame(true, $this->testModel->schemaless_attributes->bool);
        $this->assertSame(12.34, $this->testModel->schemaless_attributes->float);
    }

    /** @test */
    public function it_can_be_handled_as_an_array()
    {
        $this->testModel->schemaless_attributes['name'] = 'value';

        $this->assertEquals('value', $this->testModel->schemaless_attributes['name']);

        $this->assertTrue(isset($this->testModel->schemaless_attributes['name']));

        unset($this->testModel->schemaless_attributes['name']);

        $this->assertFalse(isset($this->testModel->schemaless_attributes['name']));

        $this->assertNull($this->testModel->schemaless_attributes['name']);
    }

    /** @test */
    public function it_can_be_counted()
    {
        $this->assertCount(0, $this->testModel->schemaless_attributes);

        $this->testModel->schemaless_attributes->name = 'value';

        $this->assertCount(1, $this->testModel->schemaless_attributes);
    }

    /** @test */
    public function it_can_add_and_save_schemaless_attributes_in_one_go()
    {
        $array = [
            'name' => 'value',
            'name2' => 'value2',
        ];

        $testModel = TestModel::create(['schemaless_attributes' => $array]);

        $this->assertEquals($array, $testModel->schemaless_attributes->all());
    }

    /** @test */
    public function it_has_a_scope_to_get_models_with_the_given_schemaless_attributes()
    {
        TestModel::truncate();

        $model1 = TestModel::create(['schemaless_attributes' => [
            'name' => 'value',
            'name2' => 'value2',
        ]]);

        $model2 = TestModel::create(['schemaless_attributes' => [
            'name' => 'value',
            'name2' => 'value2',
        ]]);

        $model3 = TestModel::create(['schemaless_attributes' => [
            'name' => 'value',
            'name2' => 'value3',
        ]]);

        $this->assertContainsModels([
            $model1, $model2,
        ], TestModel::withSchemalessAttributes(['name' => 'value', 'name2' => 'value2'])->get());

        $this->assertContainsModels([
            $model3,
        ], TestModel::withSchemalessAttributes(['name' => 'value', 'name2' => 'value3'])->get());

        $this->assertContainsModels([
        ], TestModel::withSchemalessAttributes(['name' => 'value', 'non-existing' => 'value'])->get());

        $this->assertContainsModels([
            $model1, $model2, $model3,
        ], TestModel::withSchemalessAttributes([])->get());

        $this->assertContainsModels([
            $model1, $model2, $model3,
        ], TestModel::withSchemalessAttributes('name', 'value')->get());

        $this->assertContainsModels([
        ], TestModel::withSchemalessAttributes('name', 'non-existing-value')->get());

        $this->assertContainsModels([
        ], TestModel::withSchemalessAttributes('name', 'non-existing-value')->get());
    }

    /** @test */
    public function it_can_set_multiple_attributes_one_after_the_other()
    {
        $this->testModel->schemaless_attributes->name = 'value';
        $this->testModel->schemaless_attributes->name2 = 'value2';

        $this->assertEquals([
            'name' => 'value',
            'name2' => 'value2',
        ], $this->testModel->schemaless_attributes->all());
    }

    protected function assertContainsModels(array $expectedModels, Collection $actualModels)
    {
        $assertionFailedMessage = 'Expected '.count($expectedModels).' models. Got '.$actualModels->count().' models';

        $this->assertEquals(count($expectedModels), $actualModels->count(), $assertionFailedMessage);
    }
}
