# CHANGELOG

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
