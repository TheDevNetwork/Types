[![Dependency Status][version eye shield]][version eye]
[![GitHub issues][github issues]][issues page]
[![Total Downloads][downloads shield]][packagist page]
[![License][license shield]][packagist page]
[![Latest Stable Version][latest version shield]][packagist page]
[![Scrutinizer Code Quality][scrutinizer score shield]][scrutinizer page]
[![Travis][travis build shield]][travis page]
[![Coverage Status][coveralls badge]][coveralls page]
[![SensioLabsInsight][sensio shield]][sensio page]

PhpTypes
========

##### What?

An immutable primitive wrapper library for PHP with explicit boxing/unboxing.
 (strong types even on reassignment similar to SPL_Types).

Uses the most popular* libs available in the PHP landscape and neatly wraps them in a single repo,
 providing aliased classes and some extra features not available in the base classes.
 
Long term goal is to create something like Java core libs for PHP
 although in app-land for now, so no auto-unboxing or primitive type
  casting magic methods** but damn close.

<sub>* According to packagist downloads.</sub>

<sub>** RFCs for boxing and type casting methods [[1](https://wiki.php.net/rfc/boxingandunboxing), 
[2](https://wiki.php.net/rfc/object_cast_to_types)]</sub>

###### Types

<sub>Updated list of reserved words for PHP 7 includes: int, float, bool, string.  It is why library appends `Type` to class names. Read the list [here](https://secure.php.net/manual/en/reserved.other-reserved-words.php).</sub>

- StringType
- BooleanType
- IntType*
- FloatType*
- DateTime
- Collection

<sub>* Smart use of [bcmath] or [gmp] if they are installed.</sub>

###### Credits

<a href="https://github.com/doctrine"><img src="https://raw.githubusercontent.com/TheDevNetwork/Aux/master/images/doctrine-logo.png" alt="Doctrine Collections & Doctrine Inflector" width="160px" /></a> 
 <a href="https://github.com/briannesbitt/carbon"><img src="https://raw.githubusercontent.com/TheDevNetwork/Aux/master/images/carbon-logo.png" alt="Doctrine" width="160px"  /></a>
  <a href="https://github.com/danielstjules/Stringy"><img src="https://raw.githubusercontent.com/TheDevNetwork/Aux/master/images/stringy.png" alt="Stringy" width="160px" /></a>

<sub>For further credits look at the [license](#license) section.</sub>

Example
-------
File located at `example.php`

```php
/**
 * BOXING EXAMPLE
 *
 * Use PHPDoc to get auto-completion.
 */

/** @var StringType $string */
StringType::box($string, 'foo');
echo $string; // foo

//Reassignment will remain boxed.
$string = 'bar is fun';
echo $string->dasherize(); // bar-is-fun

try {
    //Throws "TypeError" exception. This is a hard fail and you will have to box variable again. PHPism to solve.
    $string = false;
} catch (\TypeError $e) {
    //You will have to box again. We've lost the pointer.
    StringType::box($string, 'bar');
    echo $string(), PHP_EOL; // bar
    echo $string(Type::STRING), PHP_EOL; // bar
}

/**
 * UNBOXING EXAMPLE
 */

/** @var StringType $otherString */
StringType::box($otherString, 'baz');

try {
    $otherString(Type::INT);
} catch (InvalidTransformationException $e) {
    // Throws InvalidTransformationException because it's not numeric string. Let's make it one.
    $otherString = '5';
    echo $otherString(Type::INT) * 10, PHP_EOL; // 50
}

/**
 * USEFUL INTERFACES...
 */

// syllabi
echo (new StringType('syllabus'))->pluralize();

// Object is still instance of StringType.
$string = 'This is my string.';

// This is my string. It is an immutable string.
echo $string->append('It is an immutable string.');

// This is my string.
echo $string, PHP_EOL;

/**
 * THAT YOU CAN CHAIN TOGETHER
 */

//thisIsMyString.ItIsANiceString.
echo $string
        ->append('It is a nice string.')
        ->camelize(),
PHP_EOL;

/** @var IntType $int */
IntType::box($int, 100);

// 3628800
echo $int
    ->plus(1000)
    ->dividedBy(200)
    ->multipliedBy(20)
    ->squareRoot()
    ->factorial()
    ->toString()
, PHP_EOL;
```

##### Ok, but why?

Many reasons!
* **Consistency**: PHP's api is known to be inconsistent in certain areas, such as array functions, string functions, math functions, etc.
* **Stricter Typing**: Even with PHP 7 strict types, PHP remains very loosely typed. With this you have a much stricter landscape.
* **Lack of core scalar wrappers**: Most modern languages include an object version of primitives to use, PHP does not.
* **Usability**: The underlying libraries (carbon, stringy, doctrine collections & inflector) are all 
 extremely popular. Rather than reinventing the wheel, it's best to leverage those.
* **Less bugs**
* **Less bugs** (yes, I know I put that twice)

For performance questions check out the [documentation file][performance-doc] located at

    docs/performance.md

Documentation
-------------

##### Requirements

PHP 7.0 or above.

##### Installation

Using CLI:

```bash
php composer.phar require tdn/php-types:*@stable
```

In the `composer.json` file:
```json
{
    "require": {
        "tdn/php-types": "*@stable"
    }
}
```

##### In-Depth
Please checkout the [online documentation] or [main documentation file] located at

    docs/index.md

##### API

Please checkout the [online API] or run `vendor/bin/robo documentation:build` to build local documentation.

Milestones
----------

- [ ] x.x
  - [ ] PHP Extension?
- [ ] 3.0.1
  - [ ] FileSystem (Symfony FileSystem Component)
  - [ ] Finder (Symfony Finder Component)
  - [ ] File (Symfony Finder Component)
  - [ ] Money (MoneyPHP)
- [x] 3.0.0
  - [x] Updated codebase to PHP 7. (BC Incompatible)
  - [x] Removed invalid class names. (BC incompatible)
  - [x] Cleaner API (BC incompatible)
  - [x] Boxing/Unboxing
  - [x] Added Collection
- [X] 2.0 (Last supported release: 2.0.1)
  - [x] Prepared APIs for PHP 7 compatibility.

Contributing
------------

If you want to contribute, please read the [CONTRIBUTING](CONTRIBUTING.md).

License
-------

This library is released under the MIT license. See the complete license in the [LICENSE](LICENSE) file.

[online documentation]: http://todo
[online API]: http://todo
[performance-doc]: docs/performance.md
[bcmath]: https://secure.php.net/manual/en/book.bc.php
[gmp]: https://secure.php.net/manual/en/book.gmp.php
[main documentation file]: docs/index.md

[SensioLabsInsight]:https://insight.sensiolabs.com/projects/5d4f02af-7c43-4079-bcb0-9d57439a9a3f/big.png
[version eye shield]: https://www.versioneye.com/user/projects/55e90585211c6b001f00088b/badge.svg?style=flat-square
[version eye]: https://www.versioneye.com/user/projects/55e90585211c6b001f00088b
[github issues]: https://img.shields.io/github/issues/TheDevNetwork/PhpTypes.svg?style=flat-square
[issues page]: https://github.com/TheDevNetwork/PhpTypes/issues
[downloads shield]: https://img.shields.io/packagist/dt/tdn/php-types.svg?style=flat-square
[license shield]: https://img.shields.io/packagist/l/tdn/php-types.svg?style=flat-square
[latest version shield]: https://img.shields.io/packagist/v/tdn/php-types.svg?style=flat-square
[packagist page]: https://packagist.org/packages/tdn/php-types
[scrutinizer score shield]: https://img.shields.io/scrutinizer/g/TheDevNetwork/PhpTypes.svg?style=flat-square
[scrutinizer page]: https://scrutinizer-ci.com/g/TheDevNetwork/PhpTypes
[travis build shield]: https://img.shields.io/travis/TheDevNetwork/PhpTypes.svg?style=flat-square
[travis page]: https://travis-ci.org/TheDevNetwork/PhpTypes
[coveralls badge]: https://img.shields.io/coveralls/TheDevNetwork/PhpTypes/master.svg?style=flat-square
[coveralls page]: https://coveralls.io/github/TheDevNetwork/PhpTypes?branch=master
[sensio shield]: https://insight.sensiolabs.com/projects/5d4f02af-7c43-4079-bcb0-9d57439a9a3f/mini.png
[sensio page]: https://insight.sensiolabs.com/projects/5d4f02af-7c43-4079-bcb0-9d57439a9a3f
