<?php

namespace Tdn\PhpTypes\Tests\Type;

use Tdn\PhpTypes\Type\DateTimeType;
use Tdn\PhpTypes\Type\StringType;
use Tdn\PhpTypes\Type\Type;

/**
 * Class DateTimeTest.
 */
class DateTimeTypeTest extends AbstractTypeTest
{
    /**
     * @var DateTimeType
     */
    protected $dateTime;

    /**
     * Setup DateTime.
     */
    protected function setUp()
    {
        $this->dateTime = new DateTimeType();
    }

    public function testValue()
    {
        $this->assertEquals(new DateTimeType('2016-01-01'), (new DateTimeType('2016-01-01'))());
    }

    public function testType()
    {
        $this->assertInstanceOf('Carbon\\Carbon', $this->dateTime);
        $this->assertInstanceOf('\\DateTime', $this->dateTime);
    }

    public function testBox()
    {
        /* @var DateTimeType $date */
        DateTimeType::box($date);
        $this->assertTrue(($date instanceof DateTimeType));
        $date = '2016-01-01';
        $this->assertTrue(($date instanceof DateTimeType));
        $date = new DateTimeType('2016-05-10');
        $this->assertTrue(($date instanceof DateTimeType));
    }

    public function testUnbox()
    {
        /* @var DateTimeType $date */
        DateTimeType::box($date, '2016-01-30');
        $this->assertEquals('2016-01-30 00:00:00', $date(Type::STRING));
        $date = '2016-07-30 10:05:43';
        $this->assertEquals('2016-07-30 10:05:43', $date(Type::STRING));
        $this->assertEquals(new DateTimeType('2016-07-30 10:05:43'), $date());
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTypeCastException
     * @expectedExceptionMessage Could not cast DateTimeType to int.
     */
    public function testUnboxIntFail()
    {
        /* @var DateTimeType $date */
        DateTimeType::box($date, '2016-01-30');
        $date(Type::INT);
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTypeCastException
     * @expectedExceptionMessage Could not cast DateTimeType to array.
     */
    public function testUnboxArrayFail()
    {
        /* @var DateTimeType $date */
        DateTimeType::box($date, '2016-01-30');
        $date(Type::ARRAY);
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTypeCastException
     * @expectedExceptionMessage Could not cast DateTimeType to float.
     */
    public function testUnboxFoatFail()
    {
        /* @var DateTimeType $date */
        DateTimeType::box($date, '2016-01-30');
        $date(Type::FLOAT);
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTypeCastException
     * @expectedExceptionMessage Could not cast DateTimeType to bool.
     */
    public function testUnboxBoolFail()
    {
        /* @var DateTimeType $date */
        DateTimeType::box($date, '2016-01-30');
        $date(Type::BOOL);
    }

    /**
     * @expectedException \TypeError
     * @expectedExceptionMessageRegExp /Argument ([0-9]+) passed to (.*) must be of the type (.*), integer given.*$/
     */
    public function testBoxBreakType()
    {
        DateTimeType::box($date);
        $date = 1;
        $this->fail($date);
    }

    /**
     * @expectedException \TypeError
     * @expectedExceptionMessageRegExp /Argument ([0-9]+) passed to (.*) must be of the type string, boolean given.*$/
     */
    public function testBadBoxCall()
    {
        DateTimeType::box($date, false);
    }

    public function testFrom()
    {
        $this->assertEquals(new DateTimeType('2016-01-01'), DateTimeType::valueOf(new StringType('2016-01-01')));
        $this->assertEquals(new DateTimeType('2016-01-01'), DateTimeType::valueOf('2016-01-01'));
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform null to DateTimeType.
     */
    public function testBadFromNull()
    {
        DateTimeType::valueOf(null);
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform object to DateTimeType.
     */
    public function testBadFromObject()
    {
        DateTimeType::valueOf(new \stdClass());
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform resource to DateTimeType.
     */
    public function testBadFromResource()
    {
        DateTimeType::valueOf(tmpfile());
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform boolean to DateTimeType.
     */
    public function testBadFromBoolean()
    {
        DateTimeType::valueOf(false);
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform array to DateTimeType.
     */
    public function testBadFromArray()
    {
        DateTimeType::valueOf([]);
    }
}
