<?php

require_once 'vendor/autoload.php';

use Tdn\PhpTypes\Type\StringType;
use Tdn\PhpTypes\Exception\InvalidTransformationException;
use Tdn\PhpTypes\Type\Type;

//This is a caveat for IDE completion if you box instead of new instance.
//Box your variable. Then use throughout application.
/** @var StringType $string */
StringType::box($string, 'foo');
/**
 * var_dump($string);
class Tdn\PhpTypes\Type\StringType#2 (3) {
    protected $str =>
    string(3) "foo"
    protected $encoding =>
    string(5) "UTF-8"
    private $memoryAddress =>
    string(1) "1"
}
 */

$string = 'bar'; //Reassignment still has box wrapper.

/**
 * var_dump($string);
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
