[![Build Status](https://travis-ci.org/kherge/php.arrays.svg?branch=master)](https://travis-ci.org/kherge/php.arrays)
[![Quality Gate](https://sonarcloud.io/api/project_badges/measure?project=php.arrays&metric=alert_status)](https://sonarcloud.io/dashboard?id=php.arrays)

Arrays
======

Provides additional array functionality.

Requirements
------------

- PHP 7.1 or greater

Installation
------------

Use Composer to install the package as a dependency.

    $ composer require kherge/exception

Documentation
-------------

### AnyKey

An array with support for any kind of key.

```php
$array = new AnyKey();
```

By default, `null` array keys will append to the end of the array. This can be changed so that `null` keys are
treated as actual keys. This change will disable the ability to append to the array without a key. Array access
will always require a key to be used.

```php
$array = new AnyKey(false);
```

Array keys are used as is and are strictly matched, there is no type conversion.

```php
$array[1] = 'This is a different.';
$array[1.23] = 'Also a different key.';
```

> Unfortunately, string numbers such as `"1"` are still cast as numbers. This is probably a limitation with PHP.

Other arrays and objects can be used to access the array.

```php
$array[[1]] = 'Arrays as keys are supported.';
$array[new DateTime()] = 'And so are objects.';
```

Testing
-------

Use PHPUnit 7.0 to run the test suite.

    $ phpunit

License
-------

This library is available under the Apache 2.0 and MIT licenses.