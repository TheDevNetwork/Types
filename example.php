<?php

require_once 'vendor/autoload.php';

use Tdn\PhpTypes\Type\StringType;
use Tdn\PhpTypes\Type\IntType;
use Tdn\PhpTypes\Exception\InvalidTransformationException;
use Tdn\PhpTypes\Type\Type;

//Box your variable. Use PHPDoc to get auto-completion since this is a hack.
/** @var StringType $string */
StringType::box($string, 'foo');
echo $string; // foo
$string = 'bar is fun'; //Reassignment still has box wrapper.
echo $string->dasherize(); // bar-is-fun

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
    echo $otherString(Type::INT) * 10, PHP_EOL; // 50
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

echo $string, PHP_EOL; //This is my string.

/** @var IntType $int */
IntType::box($int, 100);
echo $int
        ->plus(1000)
        ->divideBy(200)
        ->multiplyBy(20)
        ->squareRoot()
        ->factorial()
        ->toString()
, PHP_EOL; // 3628800
