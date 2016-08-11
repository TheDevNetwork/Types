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
- DateTimeType
- CollectionType

<sub>* Smart use of [bcmath] or [gmp] if they are installed.</sub>

###### Credits

<a href="https://github.com/doctrine"><img src="https://raw.githubusercontent.com/TheDevNetwork/Aux/master/images/doctrine-logo.png" alt="Doctrine Collections & Doctrine Inflector" width="160px" /></a> 
 <a href="https://github.com/briannesbitt/carbon"><img src="https://raw.githubusercontent.com/TheDevNetwork/Aux/master/images/carbon-logo.png" alt="Doctrine" width="160px"  /></a>
  <a href="https://github.com/danielstjules/Stringy"><img src="https://raw.githubusercontent.com/TheDevNetwork/Aux/master/images/stringy.png" alt="Stringy" width="160px" /></a>

<sub>For further credits look at the [license](#license) section.</sub>

Example
-------

```php
//This is a caveat for IDE completion if you box instead of new instance.
//Box your variable. Then use throughout application.
/** @var StringType $string */
StringType::box($string, 'foo');

var_dump($string);
/**
class Tdn\PhpTypes\Type\StringType#2 (3) {
    protected $str =>
    string(3) "foo"
    protected $encoding =>
    string(5) "UTF-8"
    private $memoryAddress =>
    string(1) "1"
}
 */

$string = 'bar';
var_dump($string);
/**
class Tdn\PhpTypes\Type\StringType#4 (3) {
    protected $str =>
    string(3) "bar"
    protected $encoding =>
    string(5) "UTF-8"
    private $memoryAddress =>
    string(1) "2"
}
 */

try {
    $string = false; //Throws "TypeError" exception. This is a hard fail and you will have to box variable again.
} catch (\TypeError $e) {
    StringType::box($string, 'bar'); //You will have to box again. We've lost the pointer.
    echo $string(), PHP_EOL; // bar
    echo $string(Type::STRING), PHP_EOL; // bar
}

## UNBOXING to other types

/** @var StringType $otherString */
StringType::box($otherString, 'baz');

try {
    $otherString(Type::INT);
} catch (InvalidTransformationException $e) {
    // Throws InvalidTransformationException because it's not numeric string. Let's make it one.
    $otherString = '5';
    echo $otherString(Type::INT), PHP_EOL;
}

//Immutable objects with useful interfaces.

echo (new StringType('syllabus'))->pluralize(); // syllabi

$string = 'This is my string.'; //Object was declared as StringType earlier. Returns new intance.

echo $string->append('It is an immutable string.'); // This is my string. It is an immutable string.

// Chain methods together.
echo $string
        ->append('It is a nice string.')
        ->camelize(),
PHP_EOL; //thisIsMyString.ItIsANiceString.

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

Performance concerns? Check out this [link][performance-doc] testing instances over scalars. Sure objects are heavier, but it's negligible.

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

For performance questions check out the [documentation file][performance-doc] located at

    docs/performance.md

##### API

Please checkout the [online API] or run `vendor/bin/robo documentation:build` to build local documentation.

Milestones
----------

- [ ] 3.x
  - [ ] PHP Extension?
- [x] 3.0.0 (PHP 7)
  - [x] Boxing/Unboxing
  - [x] Cleaner API
  - [x] Expanded types
- [x] 2.x (OLD branch, no longer maintained)

Contributing
------------

If you want to contribute, please read the [CONTRIBUTING](CONTRIBUTING.md).

License
-------

This library is released under the MIT license. See the complete license in the [LICENSE](LICENSE.md) file.


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
