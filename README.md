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

#### Types

- StringType
- BooleanType
- IntType*
- FloatType*
- DateTime
- Collection

<sub>* Smart use of [bcmath] or [gmp] if they are installed.</sub>

##### Wait, what is this?

Primitive wrappers for PHP with boxing/unboxing. (strong types even on reassignment like SPL_Types).

Uses the most popular* libs available in the PHP landscape and neatly wraps them in a single repo,
 providing decorators with extra features.

This is an attempt to create something close to Java core libs for PHP, unfortunately no context casting
 or even primitive type casting magic methods** but damn close.

<sub>* Based on opinion but backed by packagist downloads =)</sub>

<sub>** RFCs for boxing and type casting methods that never got accepted [[1](https://wiki.php.net/rfc/boxingandunboxing), 
[2](https://wiki.php.net/rfc/object_cast_to_types)]</sub>

###### Type Credits

<a href="https://github.com/doctrine">
  <img src="https://raw.githubusercontent.com/TheDevNetwork/Aux/master/images/doctrine-logo.png" alt="Doctrine Collections & Doctrine Inflector" width="160px" />
</a> 
<a href="https://github.com/briannesbitt/carbon">
  <img src="https://raw.githubusercontent.com/TheDevNetwork/Aux/master/images/carbon-logo.png" alt="Doctrine" width="160px" />
</a>
<a href="https://github.com/danielstjules/Stringy">
  <img src="https://raw.githubusercontent.com/TheDevNetwork/Aux/master/images/stringy.png" alt="Stringy" width="160px" />
</a>

##### LOL! why?!

Many reasons!
* **Lack of core primitive wrappers**: The underlying libraries (carbon, stringy, doctrine collections & inflector) are all 
 extremely popular. It would be cool to have a core library built into the language with an interface like these.
* **Fluent/Consistent interfaces**: Our beloved PHP is infamous for flipping arguments in array and string functions.
* **Because why not?** Seemed fun to code.

**The library is fully functional**, but this is mainly a *CONCEPT*. 
Primitives will always yield higher performance than objects.

Example
-------
File located at `example.php`

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

##### API

Please checkout the [online API] or clone repo and run `vendor/bin/robo documentation:build` to build local documentation.

Contributing
------------

If you want to contribute, please read the [CONTRIBUTING](CONTRIBUTING.md).

License
-------

This library is released under the MIT license. See the complete license in the [LICENSE](LICENSE) file.

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
[coveralls badge]: https://img.shields.io/coveralls/TheDevNetwork/PhpTypes/develop.svg?style=flat-square
[coveralls page]: https://coveralls.io/github/TheDevNetwork/PhpTypes?branch=develop
[sensio shield]: https://insight.sensiolabs.com/projects/5d4f02af-7c43-4079-bcb0-9d57439a9a3f/mini.png
[sensio page]: https://insight.sensiolabs.com/projects/5d4f02af-7c43-4079-bcb0-9d57439a9a3f
