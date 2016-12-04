# CHANGELOG

## Unreleased

* Backwards incompatible changes introduced
  * Removed old aliases (PHP 7 is here!)
  * API Changes for all types
    * Removed `getValue` from TypeInterface.
    * Changed API of `__invoke` to resemble that of auto unboxing (although explicitly).
    * Removed `static::from` favoring `static::valueFrom` instead.
    * Other miscellaneous changes to types.
  * Moved traits to different namespace.
  * Newer dependencies
  * PHP 7 code base
  * Renamed some objects to prevent confusion / clashes.
* Added new types
  * Collections from Doctrine/Collections
  * Float
  * Int
* Added box/unbox feature.
  * BOX: Type::box($pointer, <value>);
  * UNBOX: $pointer(Type::INT) * $pointer(Type::INT);
* Added efficient memory management for pointers.

## 2.0.1 - 2015-09-22

* Added `invoke` to Boolean.
* Creating aliases for classes to avoid collisions with upcoming PHP 7.0 by deprecating future keywords.
* Updated tests.
* Updated documentation.

## 2.0.0 - 2015-09-08

* Upgraded libs
* Removed indent functions. Fringe case.
* Removed subStrBetween, subStrAfter, subStrUntil as they are available upstream now through alternative methods.
* Added valueOf to StringType. Allows transformation of other types to string.
* Added BooleanType type. Added valueOf to boolean. Added tests for BooleanType type.

## 1.1.1 - 2015-05-02

* Fixed indentSize implementation.
* Added more tests, CS file and phpunit config.

## 1.1.0 - 2015-03-18

* Changed method name from `subStrFromTo` to more logical `subStrBetween`.
* Fixed getPadSize to proper method name.

## 1.0.1 - 2015.02.25

Fixes alerts in scrutinizer.

## 1.0.0 - 2015.02.23

First release.
