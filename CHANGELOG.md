# CHANGELOG

## Unreleased

* Upgraded libs
* Removed indent functions. Fringe case.
* Removed subStrBetween, subStrAfter, subStrUntil as they are available upstream now through alternative methods.
* Added valueOf to String. Allows transformation of other types to string.
* Added Boolean type. Added valueOf to boolean. Added tests for Boolean type.

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
