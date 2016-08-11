<?php

namespace Tdn\PhpTypes\Tests\Type;

use Tdn\PhpTypes\Type\BooleanType;
use Tdn\PhpTypes\Type\CollectionType;
use Tdn\PhpTypes\Type\DateTimeType;
use Tdn\PhpTypes\Type\FloatType;
use Tdn\PhpTypes\Type\IntType;
use Tdn\PhpTypes\Type\StringType;
use Tdn\PhpTypes\Type\Type;

/**
 * Class StringTypeTest.
 */
class StringTypeTest extends AbstractTypeTest
{
    const STRING_SINGULAR = 'syllabus';
    const STRING_PLURAL = 'syllabi';
    const LOREM_IPSUM =
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt. Ipsum.';

    public function testBox()
    {
        /* @var StringType $string */
        StringType::box($string, new StringType('foo'));
        $this->assertTrue(($string instanceof StringType));
        $this->assertEquals('foo', $string());

        $string = 'bar';
        $this->assertTrue(($string instanceof StringType));
        $this->assertEquals('bar', $string());

        $string = new StringType('baz');
        $this->assertTrue(($string instanceof StringType));
        $this->assertEquals('baz', $string());

        /* @var StringType $otherString */
        StringType::box($otherString, 'qux');
        $this->assertTrue(($otherString instanceof StringType));
        $this->assertEquals('qux', $otherString());
    }

    public function testUnbox()
    {
        /* @var StringType $string */
        StringType::box($string, 'false');
        $this->assertEquals('false', $string(Type::STRING));
        $this->assertEquals('false', $string());
        $string = 'true';
        $this->assertTrue($string(Type::BOOL));
        $string = '5';
        $this->assertEquals(5, $string(Type::INT));
        $this->assertFalse($string(Type::BOOL));
        $string = '5.5';
        $this->assertEquals(5.5, $string(Type::FLOAT));
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform StringType to array.
     */
    public function testUnboxArrayFail()
    {
        /* @var StringType $string */
        StringType::box($string, 'false');
        $string(Type::ARRAY);
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform StringType to int.
     */
    public function testUnboxIntFail()
    {
        /* @var StringType $string */
        StringType::box($string, 'false');
        $string(Type::INT);
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform StringType to float.
     */
    public function testUnboxFloatFail()
    {
        /* @var StringType $string */
        StringType::box($string, 'false');
        $string(Type::FLOAT);
    }

    /**
     * @expectedException \TypeError
     * @expectedExceptionMessageRegExp /Argument ([0-9]+) passed to (.*) must be of the type string, integer given.*$/
     */
    public function testBoxBreak()
    {
        StringType::box($string, '');
        $string = 1;
        $this->fail($string);
    }

    /**
     * @expectedException \TypeError
     * @expectedExceptionMessageRegExp /Argument ([0-9]+) passed to (.*) must be of the type string, none given.*$/
     */
    public function testBadBoxCall()
    {
        StringType::box($string);
    }

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
        $this->assertEquals(new IntType(6), StringType::create(self::LOREM_IPSUM)->strpos('ipsum'));
        $this->assertEquals(new IntType(91), StringType::create(self::LOREM_IPSUM)->strpos('ipsum', 12));
        $this->assertEquals(new IntType(91), StringType::create(self::LOREM_IPSUM)->strpos('Ipsum', 0, true));
    }

    public function testStrrpos()
    {
        $this->assertEquals(new IntType(91), StringType::create(self::LOREM_IPSUM)->strrpos('Ipsum'));
        $this->assertEquals(new IntType(91), StringType::create(self::LOREM_IPSUM)->strrpos('ipsum', 46));
        $this->assertEquals(new IntType(6), StringType::create(self::LOREM_IPSUM)->strrpos('ipsum', 0, true));
    }

    public function testFrom()
    {
        $this->assertEquals('false', StringType::valueOf(false));
        $this->assertEquals('99', StringType::valueOf(99));
        $this->assertEquals('1.49', StringType::valueOf(1.49));
        $this->assertEquals('3E-5', StringType::valueOf(3E-5));
        $this->assertEquals('bar', StringType::valueOf('bar'));
        $this->assertEquals('1, 2, 3, 4', StringType::valueOf([1, 2, 3, 4]));
        $this->assertEquals('stream', StringType::valueOf(tmpfile()));
        $this->assertEquals(StringType::create('5'), StringType::valueOf(new IntType(5)));
        $this->assertEquals(StringType::create('false'), StringType::valueOf(new BooleanType(false)));
        $this->assertEquals(StringType::create('11.36'), StringType::valueOf(new FloatType(11.36)));
        $this->assertEquals(new StringType('foo, bar'), StringType::valueOf(new CollectionType(['foo', 'bar'])));
        $this->assertEquals('foo', StringType::valueOf(new class(0)
        {
            public function __toString()
            {
                return 'foo';
            }
        }));
    }

    public function testTransmutable()
    {
        $this->assertEquals(new BooleanType(false), StringType::create('off')->toBool());
        $this->assertEquals(new BooleanType(false), StringType::create('false')->toBool());
        $this->assertEquals(new BooleanType(false), StringType::create('no')->toBool());
        $this->assertEquals(new BooleanType(true), StringType::create('on')->toBool());
        $this->assertEquals(new BooleanType(true), StringType::create('true')->toBool());
        $this->assertEquals(new BooleanType(true), StringType::create('yes')->toBool());
        $this->assertEquals(new IntType(1), StringType::create('1')->toInt());
        $this->assertEquals(new IntType(0), StringType::create('0')->toInt());
        $this->assertEquals(new FloatType(66.547), StringType::create('66.547')->toFloat());
        $this->assertEquals(new CollectionType(['baz qux pie']), StringType::create('baz qux pie')->toCollection());
        $this->assertEquals(new DateTimeType('2016-01-01'), StringType::create('2016-01-01')->toDateTimeType());
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Method has been deprecated. Use "toBool" instead.
     */
    public function testDeprecatedMethod()
    {
        StringType::create('true')->toBoolean();
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform object to StringType.
     */
    public function testBadFromObject()
    {
        StringType::valueOf(new \stdClass());
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform null to StringType.
     */
    public function testBadFromNull()
    {
        StringType::valueOf(null);
    }
}