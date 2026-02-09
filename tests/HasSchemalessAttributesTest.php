<?php

namespace Spatie\SchemalessAttributes\Tests;

use Illuminate\Support\Collection;

use function PHPUnit\Framework\assertEquals;

function assertContainsModels(array $expectedModels, Collection $actualModels): void
{
    $assertionFailedMessage = 'Expected ' . count($expectedModels) . ' models. Got ' . $actualModels->count() . ' models';

    assertEquals(count($expectedModels), $actualModels->count(), $assertionFailedMessage);
}

beforeEach(function () {
    $this->testModel = TestModel::create();
    $this->testModelUsedTrait = new TestModel();
});

test('getting a non existing schemaless attribute returns `null`')
    ->expect(fn () => $this->testModel->schemaless_attributes->non_existing)
    ->toBeNull();

test('default value can be passed when getting a non existing schemaless attribute')
    ->expect(fn () => $this->testModel->schemaless_attributes->get('non_existing', 'default'))
    ->toEqual('default');

test('a schemaless attribute can be set', function () {
    $this->testModel->schemaless_attributes->name = 'value';

    expect($this->testModel->schemaless_attributes->name)->toEqual('value');
});

test('a schemaless attribute can be set from JSON', function () {
    $this->testModel->schemaless_attributes = json_encode(['name' => 'value']);

    expect($this->testModel->schemaless_attributes->name)->toEqual('value');
});

test('a schemaless attribute uses fallback empty array on non valid values')
    ->with('non-valid-values')
    ->tap(
        fn (mixed $value) => $this->testModel->schemaless_attributes = $value
    )
    ->expect(fn () => $this->testModel->schemaless_attributes->all())
    ->toEqual([]);

it('can determine if it has schemaless attribute', function () {
    expect($this->testModel->schemaless_attributes->has('name'))->toBeFalse();

    $this->testModel->schemaless_attributes->name = 'value';

    expect($this->testModel->schemaless_attributes->has('name'))->toBeTrue();
});

test('schemaless attributes will get saved with the model', function () {
    $this->testModel->schemaless_attributes->name = 'value';

    $this->testModel->save();

    expect($this->testModel->schemaless_attributes->name)->toEqual('value');
});

test(
    'checking existing schemaless attribute is empty with direct access',
    function () {
        $this->testModel->schemaless_attributes->name = 'value';
        $this->testModel->save();

        expect($this->testModel->schemaless_attributes->name)->not->toBeEmpty()
            ->and($this->testModel->schemaless_attributes->first_name)->toBeEmpty();
    }
);

test('checking existing schemaless attribute is empty with access via get', function () {
    $this->testModel->schemaless_attributes->name = 'value';
    $this->testModel->save();

    expect($this->testModel->schemaless_attributes->get('name'))->not->toBeEmpty()
        ->and($this->testModel->schemaless_attributes->get('first_name'))->toBeEmpty();
});

it('can handle an array', function () {
    $array = [
        'one' => 'value',
        'two' => 'another value',
    ];

    $this->testModel->schemaless_attributes->array = $array;

    expect($this->testModel->schemaless_attributes->array)->toEqual($array);
});

it('can get values using dot notation', function () {
    $this->testModel->schemaless_attributes->rey = ['side' => 'light'];
    $this->testModel->schemaless_attributes->snoke = ['side' => 'dark'];

    expect(
        $this->testModel->schemaless_attributes->get('rey.side')
    )->toEqual('light');
});

it('can set values using dot notation', function () {
    $this->testModel->schemaless_attributes->rey = ['side' => 'light'];
    $this->testModel->schemaless_attributes->set('rey.side', 'dark');

    expect(
        $this->testModel->schemaless_attributes->get('rey.side')
    )->toEqual('dark');
});

it('can get values using wildcards notation', function () {
    $this->testModel->schemaless_attributes->rey = ['sides' => [
        ['name' => 'light'],
        ['name' => 'neutral'],
        ['name' => 'dark'],
    ]];

    expect(
        $this->testModel->schemaless_attributes->get('rey.sides.*.name')
    )->toEqual(['light', 'neutral', 'dark']);
});

it('can set values using wildcard notation', function () {
    $this->testModel->schemaless_attributes->rey = ['sides' => [
        ['name' => 'light'],
        ['name' => 'neutral'],
        ['name' => 'dark'],
    ]];

    $this->testModel->schemaless_attributes->set('rey.sides.*.name', 'dark');

    expect(
        $this->testModel->schemaless_attributes->get('rey.sides.*.name')
    )->toEqual(['dark', 'dark', 'dark']);
});

it('can set all schemaless attributes at once', function () {
    $array = [
        'rey' => ['side' => 'light'],
        'snoke' => ['side' => 'dark'],
    ];

    $this->testModel->schemaless_attributes = $array;

    expect($this->testModel->schemaless_attributes->all())->toEqual($array);
});

it('can forge a single schemaless attribute', function () {
    $this->testModel->schemaless_attributes->name = 'value';

    expect($this->testModel->schemaless_attributes->name)->toEqual('value');

    $this->testModel->schemaless_attributes->forget('name');

    expect($this->testModel->schemaless_attributes->name)->toBeNull();
});

it('can forget a schemaless attribute using dot notation', function () {
    $this->testModel->schemaless_attributes->member = ['name' => 'John', 'age' => 30];

    $this->testModel->schemaless_attributes->forget('member.age');

    expect(
        $this->testModel->schemaless_attributes->member
    )->toEqual(['name' => 'John']);
});

it('can get all schemaless attributes', function () {
    $this->testModel->schemaless_attributes = ['name' => 'value'];

    expect(
        $this->testModel->schemaless_attributes->all()
    )->toEqual(['name' => 'value']);
});

it('will use the correct data type', function () {
    $this->testModel->schemaless_attributes->bool = true;
    $this->testModel->schemaless_attributes->float = 12.34;

    $this->testModel->save();

    $this->testModel->refresh();

    expect($this->testModel->schemaless_attributes)
        ->bool->toBeTrue()
        ->float->toBe(12.34);
});

it('can be handled as an array', function () {
    $this->testModel->schemaless_attributes['name'] = 'value';

    expect($this->testModel->schemaless_attributes['name'])->toEqual('value')
        ->and(isset($this->testModel->schemaless_attributes['name']))->toBeTrue();

    unset($this->testModel->schemaless_attributes['name']);

    expect(isset($this->testModel->schemaless_attributes['name']))->toBeFalse()
        ->and($this->testModel->schemaless_attributes['name'])->toBeNull();
});

it('can be counted', function () {
    expect($this->testModel->schemaless_attributes)->toHaveCount(0);

    $this->testModel->schemaless_attributes->name = 'value';

    expect($this->testModel->schemaless_attributes)->toHaveCount(1);
});

it('can be used as an arrayable', function () {
    $this->testModel->schemaless_attributes->name = 'value';

    expect($this->testModel->schemaless_attributes->all())
        ->toEqual($this->testModel->schemaless_attributes->toArray());
});

it('can add and save schemaless attributes in one go', function () {
    $array = [
        'name' => 'value',
        'name2' => 'value2',
    ];

    $testModel = TestModel::create(['schemaless_attributes' => $array]);

    expect($testModel->schemaless_attributes->all())->toEqual($array);
});

it('can and save shemaless attributes from JSON', function () {
    $array = [
        'name' => 'value',
        'name2' => 'value2',
    ];

    $testModel = TestModel::create(['schemaless_attributes' => json_encode($array)]);

    expect($testModel->schemaless_attributes->all())->toEqual($array);
});

it('can save schemaless attributes as `null` when non valid valid values', function (mixed $value) {
    $testModel = TestModel::create(['schemaless_attributes' => $value]);

    expect($testModel->getAttributes()['schemaless_attributes'])->toBeNull();
})->with('non-valid-values');

it('has a scope to get models with the given schemaless attributes', function () {
    TestModel::truncate();

    $model1 = TestModel::create(['schemaless_attributes' => [
        'name' => 'value',
        'name2' => 'value2',
        'arr' => [
            'subKey1' => 'subVal1',
        ],
    ]]);

    $model2 = TestModel::create(['schemaless_attributes' => [
        'name' => 'value',
        'name2' => 'value2',
        'arr' => [
            'subKey1' => 'subVal1',
        ],
    ]]);

    $model3 = TestModel::create(['schemaless_attributes' => [
        'name' => 'value',
        'name2' => 'value3',
        'arr' => [
            'subKey1' => 'subVal2',
        ],
    ]]);

    assertContainsModels([
        $model1, $model2,
    ], TestModel::withSchemalessAttributes(['name' => 'value', 'name2' => 'value2'])->get());

    assertContainsModels([
        $model3,
    ], TestModel::withSchemalessAttributes(['name' => 'value', 'name2' => 'value3'])->get());

    assertContainsModels([], TestModel::withSchemalessAttributes(['name' => 'value', 'non-existing' => 'value'])->get());

    assertContainsModels([
        $model1, $model2, $model3,
    ], TestModel::withSchemalessAttributes([])->get());

    assertContainsModels([
        $model1, $model2, $model3,
    ], TestModel::withSchemalessAttributes('name', 'value')->get());

    assertContainsModels([
        $model1, $model2,
    ], TestModel::withSchemalessAttributes('name2', '!=', 'value3')->get());

    assertContainsModels([
        $model3,
    ], TestModel::withSchemalessAttributes('arr->subKey1', 'subVal2')->get());

    assertContainsModels([
        $model1, $model2,
    ], TestModel::withSchemalessAttributes('arr->subKey1', '!=', 'subVal2')->get());

    assertContainsModels([], TestModel::withSchemalessAttributes('name', 'non-existing-value')->get());
});

it('can set multiple attributes one after the other', function () {
    $this->testModel->schemaless_attributes->name = 'value';
    $this->testModel->schemaless_attributes->name2 = 'value2';

    expect($this->testModel->schemaless_attributes->all())->toEqual([
        'name' => 'value',
        'name2' => 'value2',
    ]);
});

it('returns an array that can be looped', function () {
    $this->testModel->schemaless_attributes->name = 'value';
    $this->testModel->schemaless_attributes->name2 = 'value2';

    $attributes = $this->testModel->schemaless_attributes->all();

    expect($attributes)->toHaveCount(2);

    foreach ($attributes as $key => $value) {
        expect([$key, $value])->each->not->toBeNull();
    }
});

it('can multiple attributes at once by passing an array argument', function () {
    $this->testModel->schemaless_attributes->set([
        'foo' => 'bar',
        'baz' => 'buzz',
        'arr' => [
            'subKey1' => 'subVal1',
            'subKey2' => 'subVal2',
        ],
    ]);

    expect($this->testModel->schemaless_attributes->foo)->toEqual('bar')
        ->and($this->testModel->schemaless_attributes->arr)->toHaveCount(2)
        ->and($this->testModel->schemaless_attributes->arr['subKey1'])->toEqual('subVal1');
});

test('if an iterable is passed to set it will defer to `setMany`', function () {
    $this->testModel->schemaless_attributes->set([
        'foo' => 'bar',
        'baz' => 'buzz',
        'arr' => [
            'subKey1' => 'subVal1',
            'subKey2' => 'subVal2',
        ],
    ]);

    expect($this->testModel->schemaless_attributes->foo)->toEqual('bar')
        ->and($this->testModel->schemaless_attributes->arr)->toHaveCount(2)
        ->and($this->testModel->schemaless_attributes->arr['subKey1'])->toEqual('subVal1');
});

it('can call collection method pop', function () {
    $this->testModel->schemaless_attributes->set([
        'foo' => 'bar',
        'baz' => 'buzz',
        'arr' => [
            'subKey1' => 'subVal1',
            'subKey2' => 'subVal2',
        ],
    ]);

    $item = $this->testModel->schemaless_attributes->pop();

    expect($item)->toEqual([
        'subKey1' => 'subVal1',
        'subKey2' => 'subVal2',
    ]);

    expect($this->testModel->schemaless_attributes->toArray())->toEqual([
        'foo' => 'bar',
        'baz' => 'buzz',
    ]);
});

it('can call collection method `sum`', function () {
    $this->testModel->schemaless_attributes->set([
        ['price' => 10],
        ['price' => 5],
    ]);

    expect(
        $this->testModel->schemaless_attributes->sum('price')
    )->toEqual(15)
        ->and(
            $this->testModel->schemaless_attributes->toArray()
        )->toEqual([
            ['price' => 10],
            ['price' => 5],
        ]);
});

it('can call collection method `slice`', function () {
    $this->testModel->schemaless_attributes->set([
        'foo' => 'bar',
        'baz' => 'buzz',
        'lorem' => 'ipsum',
        'dolor' => 'amet',
    ]);

    expect(
        $this->testModel->schemaless_attributes->slice(1, 2)->toArray()
    )->toEqual([
        'baz' => 'buzz',
        'lorem' => 'ipsum',
    ]);

    expect(
        $this->testModel->schemaless_attributes->toArray()
    )->toEqual([
        'foo' => 'bar',
        'baz' => 'buzz',
        'lorem' => 'ipsum',
        'dolor' => 'amet',
    ]);
});

it('can call collection method only', function () {
    $this->testModel->schemaless_attributes->set([
        'foo' => 'bar',
        'baz' => 'buzz',
        'lorem' => 'ipsum',
        'dolor' => 'amet',
    ]);

    expect(
        $this->testModel->schemaless_attributes->only('baz', 'dolor')->toArray()
    )->toEqual([
        'baz' => 'buzz',
        'dolor' => 'amet',
    ]);

    expect(
        $this->testModel->schemaless_attributes->toArray()
    )->toEqual([
        'foo' => 'bar',
        'baz' => 'buzz',
        'lorem' => 'ipsum',
        'dolor' => 'amet',
    ]);
});

it('throws a JsonException when setting a non-JSON-encodable value', function (mixed $value) {
    $this->testModel->schemaless_attributes->name = $value;
})->with([
    'NAN' => NAN,
    'INF' => INF,
    '-INF' => -INF,
])->throws(\JsonException::class);

test('an schemaless attribute can be set if the hasSchemalessAttribute trait is used', function () {
    $this->testModelUsedTrait->schemaless_attributes->name = 'value';

    expect($this->testModelUsedTrait->schemaless_attributes->name)->toEqual('value');
});
