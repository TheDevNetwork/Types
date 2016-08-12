<?php

namespace Tdn\PhpTypes\Tests\Type;

use Tdn\PhpTypes\Type\DateTime;
use Tdn\PhpTypes\Type\StringType;
use Tdn\PhpTypes\Type\Type;

/**
 * Class DateTimeTest.
 */
class DateTimeTest extends AbstractTypeTest
{
    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * Setup DateTime.
     */
    protected function setUp()
    {
        $this->dateTime = new DateTime();
    }

    public function testValue()
    {
        $this->assertEquals(new DateTime('2016-01-01'), (new DateTime('2016-01-01'))());
    }

    public function testType()
    {
        $this->assertInstanceOf('Carbon\\Carbon', $this->dateTime);
        $this->assertInstanceOf('\\DateTime', $this->dateTime);
    }

    public function testBox()
    {
        /* @var DateTime $date */
        DateTime::box($date);
        $this->assertTrue(($date instanceof DateTime));
        $date = '2016-01-01';
        $this->assertTrue(($date instanceof DateTime));
        $date = new DateTime('2016-05-10');
        $this->assertTrue(($date instanceof DateTime));
    }

    public function testUnbox()
    {
        /* @var DateTime $date */
        DateTime::box($date, '2016-01-30');
        $this->assertEquals('2016-01-30 00:00:00', $date(Type::STRING));
        $date = '2016-07-30 10:05:43';
        $this->assertEquals('2016-07-30 10:05:43', $date(Type::STRING));
        $this->assertEquals(new DateTime('2016-07-30 10:05:43'), $date());
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTypeCastException
     * @expectedExceptionMessage Could not cast DateTime to int.
     */
    public function testUnboxIntFail()
    {
        /* @var DateTime $date */
        DateTime::box($date, '2016-01-30');
        $date(Type::INT);
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTypeCastException
     * @expectedExceptionMessage Could not cast DateTime to array.
     */
    public function testUnboxArrayFail()
    {
        /* @var DateTime $date */
        DateTime::box($date, '2016-01-30');
        $date(Type::ARRAY);
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTypeCastException
     * @expectedExceptionMessage Could not cast DateTime to float.
     */
    public function testUnboxFoatFail()
    {
        /* @var DateTime $date */
        DateTime::box($date, '2016-01-30');
        $date(Type::FLOAT);
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTypeCastException
     * @expectedExceptionMessage Could not cast DateTime to bool.
     */
    public function testUnboxBoolFail()
    {
        /* @var DateTime $date */
        DateTime::box($date, '2016-01-30');
        $date(Type::BOOL);
    }

    /**
     * @expectedException \TypeError
     * @expectedExceptionMessageRegExp /Argument ([0-9]+) passed to (.*) must be of the type (.*), integer given.*$/
     */
    public function testBoxBreakType()
    {
        DateTime::box($date);
        $date = 1;
        $this->fail($date);
    }

    /**
     * @expectedException \TypeError
     * @expectedExceptionMessageRegExp /Argument ([0-9]+) passed to (.*) must be of the type string, boolean given.*$/
     */
    public function testBadBoxCall()
    {
        DateTime::box($date, false);
    }

    public function testFrom()
    {
        $this->assertEquals(new DateTime('2016-01-01'), DateTime::valueOf(new StringType('2016-01-01')));
        $this->assertEquals(new DateTime('2016-01-01'), DateTime::valueOf('2016-01-01'));
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform null to DateTime.
     */
    public function testBadFromNull()
    {
        DateTime::valueOf(null);
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform object to DateTime.
     */
    public function testBadFromObject()
    {
        DateTime::valueOf(new \stdClass());
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform resource to DateTime.
     */
    public function testBadFromResource()
    {
        DateTime::valueOf(tmpfile());
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform boolean to DateTime.
     */
    public function testBadFromBoolean()
    {
        DateTime::valueOf(false);
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform array to DateTime.
     */
    public function testBadFromArray()
    {
        DateTime::valueOf([]);
    }
}
