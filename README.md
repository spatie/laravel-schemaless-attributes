# Add schemaless attributes to Eloquent models

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-schemaless-attributes.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-schemaless-attributes)
[![Build Status](https://img.shields.io/travis/spatie/laravel-schemaless-attributes/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-schemaless-attributes)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-schemaless-attributes.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-schemaless-attributes)
[![StyleCI](https://styleci.io/repos/132581720/shield?branch=master)](https://styleci.io/repos/132581720)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-schemaless-attributes.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-schemaless-attributes)

Wouldn't it be cool if you could just have a bit of the spirit of nosql available in Eloquent? This package does just that. It provides a trait that, when applied on a model, allow you to store arbritrary values in your model.

Here are a few examples

```php
// add and retrieve an attribute
$yourModel->schemaless_attributes->name = 'value';
$yourModel->schemaless_attributes->name; // returns 'value';

// you can also use the array approach

$yourModel->schemaless_attributes['name'] = 'value';
$yourModel->schemaless_attributes['name'] // returns 'value';

// setting multiple values in one go
$yourModel->schemaless_attributes = [
   'rey' => ['side' => 'light'], 
   'snoke' => ['side' => 'dark']
];

// retrieving values using dot notation
$yourModel->schemaless_attributes->get('rey.side'); // returns 'light';
```


## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-schemaless-attributes
```

## Usage

``` php
$skeleton = new Spatie\Skeleton();
echo $skeleton->echoPhrase('Hello, Spatie!');
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

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
