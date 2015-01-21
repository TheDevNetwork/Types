<?php

namespace Tdn\PhpTypes\Tests;

use Tdn\PhpTypes\Type\String;

/**
 * Class String
 * @package Tdn\PhpTypes\Tests
 */
class StringTest extends \PHPUnit_Framework_TestCase
{
    const STRING_SINGULAR = "syllabus";
    const STRING_PLURAL   = "syllabi";

    public function testSingular()
    {
        $this->assertEquals((string) (new String(self::STRING_PLURAL))->singularize(), self::STRING_SINGULAR);
    }

    public function testPluralize()
    {
        $this->assertEquals((string) (new String(self::STRING_SINGULAR))->pluralize(), self::STRING_PLURAL);
    }

}

