<?php

use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;
use Spatie\SchemalessAttributes\Tests\TestModelUsedTrait;

beforeEach(function () {
    $this->testModel = new TestModelUsedTrait();
});

test('SchemalessAttributes cast as array initialize schemaless attributes trait')
    ->expect(fn () => $this->testModel->getCasts()['schemaless_attributes'])
    ->toBe(SchemalessAttributes::class);

test('other schemaless attributes cast as array initialize schemaless attributes trait')
    ->expect(fn () => $this->testModel->getCasts()['other_schemaless_attributes'])
    ->toBe(SchemalessAttributes::class);

test('getSchemalessAttributes')
    ->expect(fn () => $this->testModel->getSchemalessAttributes())
    ->toEqual(['schemaless_attributes', 'other_schemaless_attributes']);

test('get')
    ->expect(fn () => $this->testModel->schemaless_attributes->name)
    ->toBeNull();

test('parent get')
    ->expect(fn () => $this->testModel->not_existing_schemaless)
    ->toBeNull();
