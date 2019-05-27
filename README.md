# Add schemaless attributes to Eloquent models

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-schemaless-attributes.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-schemaless-attributes)
[![Build Status](https://img.shields.io/travis/spatie/laravel-schemaless-attributes/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-schemaless-attributes)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-schemaless-attributes.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-schemaless-attributes)
[![StyleCI](https://styleci.io/repos/132581720/shield?branch=master)](https://styleci.io/repos/132581720)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-schemaless-attributes.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-schemaless-attributes)

Wouldn't it be cool if you could have a bit of the spirit of NoSQL available in Eloquent? This package does just that. It provides a trait that when applied on a model, allows you to store arbitrary values in a single JSON column.

Here are a few examples. We're using the `extra_attributes` column here, but you can name it [whatever you want](#adding-the-column-where-the-schemaless-attributes-will-be-stored).

```php
// add and retrieve an attribute
$yourModel->extra_attributes->name = 'value';
$yourModel->extra_attributes->name; // returns 'value'

// you can also use the array approach
$yourModel->extra_attributes['name'] = 'value';
$yourModel->extra_attributes['name'] // returns 'value'

// setting multiple values in one go
$yourModel->extra_attributes = [
   'rey' => ['side' => 'light'],
   'snoke' => ['side' => 'dark']
];

// setting/updating multiple values in one go via set()
$yourModel->extra_attributes->set([
   'han' => ['side' => 'light'],
   'snoke' => ['side' => 'dark']
]);

// retrieving values using dot notation
$yourModel->extra_attributes->get('rey.side'); // returns 'light'

// retrieve default value when attribute is not exists
$yourModel->extra_attributes->get('non_existing', 'default'); // returns 'default'

// it has a scope to retrieve all models with the given schemaless attributes
$yourModel->withSchemalessAttributes(['name' => 'value', 'name2' => 'value2'])->get();
```

## Requirements

This package requires a database with support for `json` columns like MySQL 5.7 or higher.

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-schemaless-attributes
```

The schemaless attributes will be stored in a json column on the table of your model. Let's add that column and prepare the model.

### Adding the column where the schemaless attributes will be stored

Add a migration for all models where you want to add schemaless attributes to. You should use `schemalessAttributes` method on `Blueprint` to add a column. The argument you give to `schemalessAttributes` is the column name that will be added. You can use any name you'd like. You're also free to add as many schemaless attribute columns to your table as you want. In all examples of this readme we'll use a single column named `extra_attributes`.

```php
Schema::table('your_models', function (Blueprint $table) {
    $table->schemalessAttributes('extra_attributes');
});
```

### Preparing the model

In order to work with the schemaless attributes you'll need to add a cast, an accessor and a scope on your model. Here's an example of what you need to add if you've chosen `extra_attributes` as your column name.

```php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\SchemalessAttributes\SchemalessAttributes;

class TestModel extends Model
{
    // ...

    public $casts = [
        'extra_attributes' => 'array',
    ];

    public function getExtraAttributesAttribute(): SchemalessAttributes
    {
        return SchemalessAttributes::createForModel($this, 'extra_attributes');
    }

    public function scopeWithExtraAttributes(): Builder
    {
        return SchemalessAttributes::scopeWithSchemalessAttributes('extra_attributes');
    }

    // ...
}
```

If you want to reuse this behaviour across multiple models you could opt to put the function in a trait of your own. Here's what that trait could look like:

```php
namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\SchemalessAttributes\SchemalessAttributes;

trait HasSchemalessAttributes
{
    public function getExtraAttributesAttribute(): SchemalessAttributes
    {
       return SchemalessAttributes::createForModel($this, 'extra_attributes');
    }

    public function scopeWithExtraAttributes(): Builder
    {
        return SchemalessAttributes::scopeWithSchemalessAttributes('extra_attributes');
    }
}
```

## Usage

### Getting and setting schemaless attributes

This is the easiest way to get and set schemaless attributes:

```php
$yourModel->extra_attributes->name = 'value';

$yourModel->extra_attributes->name; // Returns 'value'
```

Alternatively you can use an array approach:

```php
$yourModel->extra_attributes['name'] = 'value';

$yourModel->extra_attributes['name']; // Returns 'value'
```

You can replace all existing schemaless attributes by assigning an array to it.

```php
// All existing schemaless attributes will be replaced
$yourModel->extra_attributes = ['name' => 'value'];
$yourModel->extra_attributes->all(); // Returns ['name' => 'value']
```

You can also opt to use `get` and `set`. The methods have support for dot notation.

```php
$yourModel->extra_attributes = [
   'rey' => ['side' => 'light'],
   'snoke' => ['side' => 'dark'],
];
$yourModel->extra_attributes->set('rey.side', 'dark');

$yourModel->extra_attributes->get('rey.side'); // Returns 'dark
```

You can also pass a default value to the `get` method.

```php
$yourModel->extra_attributes->get('non_existing', 'default'); // Returns 'default'
```

### Persisting schemaless attributes

To persist schemaless attributes you should, just like you do for normal attributes, call `save()` on the model.

```php
$yourModel->save(); // Persists both normal and schemaless attributes
```

### Retrieving models with specific schemaless attributes

Here's how you can use the provided scope.

```php
// Returns all models that have all the given schemaless attributes
$yourModel->withExtraAttributes(['name' => 'value', 'name2' => 'value2'])->get();
```

If you only want to search on a single custom attribute, you can use the scope like this

```php
// returns all models that have a schemaless attribute `name` set to `value`
$yourModel->withExtraAttributes('name', 'value')->get();
```

## Testing

First create a MySQL database named `laravel_schemaless_attributes`. After that you can run the tests with:

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Postcardware

You're free to use this package, but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Samberstraat 69D, 2060 Antwerp, Belgium.

We publish all received postcards [on our company website](https://spatie.be/en/opensource/postcards).

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## Support us

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

Does your business depend on our contributions? Reach out and support us on [Patreon](https://www.patreon.com/spatie).
All pledges will be dedicated to allocating workforce on maintenance and new awesome stuff.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
