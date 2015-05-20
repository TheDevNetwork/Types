<?php

namespace Tdn\PhpTypes\Tests;

use Tdn\PhpTypes\Tests\Fixtures\StringableObject;
use Tdn\PhpTypes\Type\String;

/**
 * Class StringTest
 * @package Tdn\PhpTypes\Tests
 */
class StringTest extends \PHPUnit_Framework_TestCase
{
    const STRING_SINGULAR = "syllabus";
    const STRING_PLURAL   = "syllabi";
    const LOREM_IPSUM     =
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt. Ipsum.';

    public function testSingular()
    {
        $this->assertEquals(self::STRING_SINGULAR, String::create(self::STRING_PLURAL)->singularize());
    }

    public function testPluralize()
    {
        $this->assertEquals(self::STRING_PLURAL, String::create(self::STRING_SINGULAR)->pluralize());
    }

    public function testStrpos()
    {
        $this->assertEquals(6, String::create(self::LOREM_IPSUM)->strpos('ipsum'));
        $this->assertEquals(91, String::create(self::LOREM_IPSUM)->strpos('ipsum', 12));
        $this->assertEquals(91, String::create(self::LOREM_IPSUM)->strpos('Ipsum', 0, true));
    }

    public function testStrrpos()
    {
        $this->assertEquals(91, String::create(self::LOREM_IPSUM)->strrpos('Ipsum'));
        $this->assertEquals(91, String::create(self::LOREM_IPSUM)->strrpos('ipsum', 46));
        $this->assertEquals(6, String::create(self::LOREM_IPSUM)->strrpos('ipsum', 0, true));
    }

    public function testValueOf()
    {
        $this->assertEquals('false', String::valueOf(false));
        $this->assertEquals('99', String::valueOf(99));
        $this->assertEquals('1.49', String::valueOf(1.49));
        $this->assertEquals('3E-5', String::valueOf(3E-5));
        $this->assertEquals('bar', String::valueOf('bar'));
        $this->assertEquals('1, 2, 3, 4', String::valueOf([1, 2, 3, 4]));
        $this->assertEquals('foo', String::valueOf(new StringableObject()));
        $this->assertEquals('stream', String::valueOf(tmpfile()));
        $this->assertEquals('', String::valueOf(null));
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform stdClass to string.
     */
    public function testBadValueOfObject()
    {
        String::valueOf(new \StdClass());
    }
}
