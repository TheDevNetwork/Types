<?php

namespace Tdn\PhpTypes\Tests;

use Tdn\PhpTypes\Tests\Fixtures\StringableObject;
use Tdn\PhpTypes\Type\StringType;

/**
 * Class StringTypeTest
 * @package Tdn\PhpTypes\Tests
 */
class StringTypeTest extends \PHPUnit_Framework_TestCase
{
    const STRING_SINGULAR = "syllabus";
    const STRING_PLURAL   = "syllabi";
    const LOREM_IPSUM     =
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt. Ipsum.';

    public function testSingular()
    {
        $this->assertEquals(self::STRING_SINGULAR, StringType::create(self::STRING_PLURAL)->singularize());
    }

    public function testPluralize()
    {
        $this->assertEquals(self::STRING_PLURAL, StringType::create(self::STRING_SINGULAR)->pluralize());
    }

    public function testStrpos()
    {
        $this->assertEquals(6, StringType::create(self::LOREM_IPSUM)->strpos('ipsum'));
        $this->assertEquals(91, StringType::create(self::LOREM_IPSUM)->strpos('ipsum', 12));
        $this->assertEquals(91, StringType::create(self::LOREM_IPSUM)->strpos('Ipsum', 0, true));
    }

    public function testStrrpos()
    {
        $this->assertEquals(91, StringType::create(self::LOREM_IPSUM)->strrpos('Ipsum'));
        $this->assertEquals(91, StringType::create(self::LOREM_IPSUM)->strrpos('ipsum', 46));
        $this->assertEquals(6, StringType::create(self::LOREM_IPSUM)->strrpos('ipsum', 0, true));
    }

    public function testValueOf()
    {
        $this->assertEquals('false', StringType::valueOf(false));
        $this->assertEquals('99', StringType::valueOf(99));
        $this->assertEquals('1.49', StringType::valueOf(1.49));
        $this->assertEquals('3E-5', StringType::valueOf(3E-5));
        $this->assertEquals('bar', StringType::valueOf('bar'));
        $this->assertEquals('1, 2, 3, 4', StringType::valueOf([1, 2, 3, 4]));
        $this->assertEquals('foo', StringType::valueOf(new StringableObject()));
        $this->assertEquals('stream', StringType::valueOf(tmpfile()));
        $this->assertEquals('', StringType::valueOf(null));
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform stdClass to string.
     */
    public function testBadValueOfObject()
    {
        StringType::valueOf(new \StdClass());
    }
}
