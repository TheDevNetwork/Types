<?php

require_once 'vendor/autoload.php';

use Tdn\PhpTypes\Type\StringType;
use Tdn\PhpTypes\Type\IntType;
use Tdn\PhpTypes\Exception\InvalidTransformationException;
use Tdn\PhpTypes\Type\Type;

/** @var StringType $string */
StringType::box($string, 'foo');
echo $string; // foo

//Reassignment will remain boxed.
$string = 'bar is fun';
echo $string->dasherize(); // bar-is-fun

try {
    //Throws "TypeError" exception.
    $string = false;
} catch (\TypeError $e) {
    echo $string(), PHP_EOL; // bar
    echo $string(Type::STRING), PHP_EOL; // bar
}

/** @var StringType $otherString */
StringType::box($otherString, 'baz');

try {
    $otherString(Type::INT);
} catch (InvalidTransformationException $e) {
    // Throws InvalidTransformationException because it's not numeric string. Let's make it one.
    $otherString = '5';
    echo $otherString(Type::INT) * 10, PHP_EOL; // 50
}

// syllabi
echo (new StringType('syllabus'))->pluralize();

// Object is still instance of StringType.
$string = 'This is my string.';

// This is my string. It is an immutable string.
echo $string->append('It is an immutable string.');

// This is my string.
echo $string, PHP_EOL;

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
