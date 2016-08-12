#!/usr/bin/env php
<?php

namespace Tdn\PhpTypes;

include_once 'vendor/autoload.php';

/**
 * @param $limit
 * @return float
 */
function getPrimitiveTime($limit) : float {
    $primitiveStart = microtime(true);

    for ($i = 0; $i < $limit; $i++) {
        $bool = 'bool' . $i;
        $string = 'string' . $i;
        $int = 'int' . $i;
        $float = 'float' . $i;
        $array = 'array' . $i;
        $datetime = 'datetime' . $i;
        $$bool = false;
        $$string = '';
        $$int = 1;
        $$float = 1.0;
        $$array = [];
        $$datetime = new \DateTime();
    }

    $primitiveEnd = microtime(true);

    return ($primitiveEnd - $primitiveStart) / (6 * $limit);
}

function getObjectTypeTime($limit) : float {
    $objectStart = microtime(true);

    for ($i = 0; $i < $limit; $i++) {
        $bool = 'bool' . $i;
        $string = 'string' . $i;
        $int = 'int' . $i;
        $float = 'float' . $i;
        $array = 'array' . $i;
        $datetime = 'datetime' . $i;
        \Tdn\PhpTypes\Type\BooleanType::box($$bool, false);
        \Tdn\PhpTypes\Type\StringType::box($$string, 'foo');
        \Tdn\PhpTypes\Type\IntType::box($$int, 1);
        \Tdn\PhpTypes\Type\FloatType::box($$float, 1.0);
        \Tdn\PhpTypes\Type\Collection::box($$array, []);
        \Tdn\PhpTypes\Type\DateTime::box($$datetime, new \Tdn\PhpTypes\Type\DateTime());
    }

    $objectEnd = microtime(true);

    return ($objectEnd - $objectStart) / (6 * $limit);
}

/**
 * TEST PARAMETERS
 *
 * limit = 16666 => 100,000 variables
 * limit = 83333 => 500,000 variables
 * limit = 166666 => 1,000,000 variables
 */
echo 'TESTS', PHP_EOL;
echo '====================================================================',PHP_EOL;
echo 'X=100,000 Variables' . PHP_EOL;
echo sprintf('Primitive total time: %s/var', getPrimitiveTime(16666)), PHP_EOL;
$memoryPrimitive = round((memory_get_usage() / 1048576), 2);
echo sprintf('Primitive memory: %sMB', $memoryPrimitive), PHP_EOL;
echo sprintf('ObjectTypes total time: %s/var', getObjectTypeTime(16666)), PHP_EOL;
$memoryObj = round((memory_get_usage() / 1048576), 2);
echo sprintf('ObjectTypes memory: %sMB', ($memoryObj - $memoryPrimitive)), PHP_EOL;
echo '------ ',PHP_EOL;
echo 'X=500,000 Variables' . PHP_EOL;
echo sprintf('Primitive total time: %s/var', getPrimitiveTime(83333)), PHP_EOL;
$memoryPrimitive500 = round((memory_get_usage() / 1048576), 2);
echo sprintf('Primitive memory: %sMB', ($memoryPrimitive500 - $memoryObj)), PHP_EOL;
echo sprintf('ObjectTypes total time: %s/var', getObjectTypeTime(83333)), PHP_EOL;
$memoryObj500 = round((memory_get_usage() / 1048576), 2);
echo sprintf('ObjectTypes memory: %sMB', ($memoryObj500 - $memoryPrimitive500)), PHP_EOL;
echo '------ ',PHP_EOL;
echo 'X=1,000,000 Variables' . PHP_EOL;
echo sprintf('Primitive total time: %s/var', getPrimitiveTime(166666)), PHP_EOL;
$memoryPrimitive1000 = round((memory_get_usage() / 1048576), 2);
echo sprintf('Primitive memory: %sMB', ($memoryPrimitive1000 - $memoryObj500)), PHP_EOL;
echo sprintf('ObjectTypes total time: %s/var', getObjectTypeTime(166666)), PHP_EOL;
$memoryObj1000 = round((memory_get_usage() / 1048576), 2);
echo sprintf('ObjectTypes memory: %sMB', $memoryObj1000 - $memoryPrimitive1000), PHP_EOL;
