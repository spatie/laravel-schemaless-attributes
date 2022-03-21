
[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/support-ukraine.svg?t=1" />](https://supportukrainenow.org)

# Add schemaless attributes to Eloquent models

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-schemaless-attributes.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-schemaless-attributes)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/spatie/laravel-schemaless-attributes/run-tests?label=tests)
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

// it has a modelScope to retrieve all models with the given schemaless attributes
$yourModel->withSchemalessAttributes(['name' => 'value', 'name2' => 'value2'])->get();

// delete key & value
$yourModel->extra_attributes->forget('key');
```

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-schemaless-attributes.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-schemaless-attributes)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Requirements

This package requires a database with support for `json` columns like MySQL 5.7 or higher.

## Installation

> For Laravel versions 6 & 7 or PHP 7, use version 1.x of this package.

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

In order to work with the schemaless attributes you'll need to add a custom cast and a scope on your model. Here's an example of what you need to add if you've chosen `extra_attributes` as your column name.

```php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;

class TestModel extends Model
{
    // ...

    public $casts = [
        'extra_attributes' => SchemalessAttributes::class,
    ];

    public function scopeWithExtraAttributes(): Builder
    {
        return $this->extra_attributes->modelScope();
    }

    // ...
}
```

If you need support for multiple schemaless columns on a single model, you should use `SchemalessAttributesTrait` trait. Here's an example of what you need to add if you've chosen `extra_attributes, other_extra_attributes` as your column names.

```php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\SchemalessAttributes\SchemalessAttributes;
use Spatie\SchemalessAttributes\SchemalessAttributesTrait;

class TestModel extends Model
{
    use SchemalessAttributesTrait;

    // ...
    
    /**
     * @var array
     */
    protected $schemalessAttributes = [
        'extra_attributes',
        'other_extra_attributes',
    ];

    public function scopeWithExtraAttributes(): Builder
    {
        return $this->extra_attributes->modelScope();
    }
    
    public function scopeWithOtherExtraAttributes(): Builder
    {
        return $this->other_extra_attributes->modelScope();
    }

    // ...
}
```


If you want to reuse this behaviour across multiple models you could opt to put the function in a trait of your own. Here's what that trait could look like:

```php
namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;

trait HasSchemalessAttributes
{
    public function initializeHasSchemalessAttributes()
    {
        $this->casts['extra_attributes'] = SchemalessAttributes::class;
    }
    
    public function scopeWithExtraAttributes(): Builder
    {
        return $this->extra_attributes->modelScope();
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

Here's how you can use the provided modelScope.

```php
// Returns all models that have all the given schemaless attributes
$yourModel->withExtraAttributes(['name' => 'value', 'name2' => 'value2'])->get();
```

If you only want to search on a single custom attribute, you can use the modelScope like this

```php
// returns all models that have a schemaless attribute `name` set to `value`
$yourModel->withExtraAttributes('name', 'value')->get();
```

Also, if you only want to search on a single custom attribute with a custom operator, you can use the modelScope like this

```php
// returns all models that have a schemaless attribute `name` starting with `value`
$yourModel->withExtraAttributes('name', 'LIKE', 'value%')->get();
```

If you only want to search on a nested custom attribute, you can use the modelScope like this

```php
// returns all models that have a schemaless nested attribute `han.side` set to `light`
$yourModel->withExtraAttributes('han->side', 'light')->get();
```

## Testing

First create a MySQL database named `laravel_schemaless_attributes`. After that you can run the tests with:

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
