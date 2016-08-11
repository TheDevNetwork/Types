<?php

namespace Tdn\PhpTypes\Tests\Type;

use Tdn\PhpTypes\Type\FloatType;
use Tdn\PhpTypes\Type\IntType;
use Tdn\PhpTypes\Type\StringType;
use Tdn\PhpTypes\Type\Type;

/**
 * Class IntTypeTest.
 */
class IntTypeTest extends AbstractTypeTest
{
    public function testBox()
    {
        /* @var IntType $int */
        IntType::box($int, new IntType(1));
        $this->assertTrue(($int instanceof IntType));
        $this->assertEquals(1.0, $int->get());

        $int = 493;
        $this->assertTrue(($int instanceof IntType));
        $this->assertEquals(493, $int->get());

        $int = new IntType(493);
        $this->assertTrue(($int instanceof IntType));
        $this->assertEquals(493, $int->get());

        /* @var IntType $otherInt */
        IntType::box($otherInt, 948);
        $this->assertTrue(($otherInt instanceof IntType));
        $this->assertEquals(948, $otherInt->get());
    }

    public function testUnbox()
    {
        /* @var IntType $int */
        IntType::box($int, 948);
        $this->assertEquals('948', $int(Type::STRING));
        $int = 493;
        $this->assertEquals(493, $int(Type::INT));
        $this->assertEquals(493.0, $int(Type::FLOAT));
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform IntType to array.
     */
    public function testUnboxArrayFail()
    {
        /* @var IntType $int */
        IntType::box($int, 948);
        $int(Type::ARRAY);
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform IntType to bool.
     */
    public function testUnboxBoolFail()
    {
        /* @var IntType $int */
        IntType::box($int, 948);
        $int(Type::BOOL);
    }

    /**
     * @expectedException \TypeError
     * @expectedExceptionMessageRegExp /Argument ([0-9]+) passed to (.*) must be of the type integer, string given.*$/
     */
    public function testBoxBreak()
    {
        IntType::box($int, 0);
        $int = '1';
        $this->fail($int);
    }

    /**
     * @expectedException \TypeError
     * @expectedExceptionMessageRegExp /Argument ([0-9]+) passed to (.*) must be of the type integer, none given.*$/
     */
    public function testBadBoxCall()
    {
        IntType::box($int);
    }

    public function testMultiply()
    {
        $this->assertEquals(new IntType(15), (new IntType(3))->multiplyBy(new IntType(5)));
        $this->assertEquals(new IntType(15), (new IntType(3))->multiplyBy(5));
        $this->assertEquals(new IntType(30), (new IntType(2))->multiplyBy(new IntType(15)));
        $this->assertEquals(new IntType(30), (new IntType(2))->multiplyBy(15));
    }

    public function testAdd()
    {
        $this->assertEquals(new IntType(15), (new IntType(10))->plus(new IntType(5)));
        $this->assertEquals(new IntType(15), (new IntType(10))->plus(5));
    }

    public function testSubstract()
    {
        $this->assertEquals(new IntType(10), (new IntType(20))->minus(new IntType(10)));
        $this->assertEquals(new IntType(10), (new IntType(20))->minus(new FloatType(10.4))); //Rounds down
        $this->assertEquals(new IntType(9), (new IntType(20))->minus(new FloatType(10.6))); //Rounds up
        $this->assertEquals(new FloatType(9), (new IntType(20))->toFloat()->minus(new FloatType(10.4)));
        $this->assertEquals(new FloatType(9), (new IntType(20))->toFloat()->minus(new FloatType(10.6)));
        $this->assertEquals(new IntType(10), (new IntType(20))->minus(10));
    }

    public function testDivideBy()
    {
        $this->assertEquals(new IntType(15), (new IntType(30))->divideBy(new IntType(2)));
        $this->assertEquals(new IntType(15), (new IntType(30))->divideBy(2));
    }

    public function testAbsolute()
    {
        $this->assertEquals(new IntType(30), (new IntType(30))->absolute());
        $this->assertEquals(new IntType(30), (new IntType(-30))->absolute());
    }

    public function testCompare()
    {
        $this->assertEquals(new IntType(1), (new IntType(5))->compare(4));
        $this->assertEquals(new IntType(1), (new IntType(6))->compare(3));
        $this->assertEquals(new IntType(1), (new IntType(1000))->compare(999));
    }

    public function testModulus()
    {
        $this->assertEquals(new IntType(5), (new IntType(5))->modulus(10));
    }

    public function testPower()
    {
        $this->assertEquals(new IntType(244140625), (new IntType(5))->power(12));
    }

    public function testSquareRoot()
    {
        $this->assertEquals(new IntType(3), (new IntType(9))->squareRoot());
        $this->assertEquals(new IntType(7), (new IntType(49))->squareRoot());
    }

    public function testNegate()
    {
        $this->assertEquals(new IntType(-49), (new IntType(49))->negate());
        $this->assertEquals(new IntType(49), (new IntType(-49))->negate());
    }

    public function testFactorial()
    {
        $this->assertEquals(new IntType(3628800), (new IntType(10))->factorial());
    }

    public function testGcd()
    {
        $this->assertEquals(new IntType(10), (new IntType(10))->gcd(50));
    }

    public function testRoot()
    {
        $this->assertEquals(new IntType(3), (new IntType(32))->root(3));
    }

    public function testNextPrime()
    {
        $this->assertEquals(new IntType(7), (new IntType(5))->getNextPrime());
    }

    public function testIsPrime()
    {
        $this->assertTrue((new IntType(5))->isPrime()->get());
        $this->assertFalse((new IntType(10))->isPrime()->get());
    }

    public function testIsPerfectSquare()
    {
        $this->assertTrue((new IntType(4))->isPerfectSquare()->get());
        $this->assertFalse((new IntType(5))->isPerfectSquare()->get());
    }

    public function testType()
    {
        $this->assertEquals('integer', gettype((new IntType(10))->get()));
    }

    public function testValue()
    {
        $this->assertEquals(5, (new IntType(5))->get());
    }

    public function testFrom()
    {
        $this->assertEquals(10, (IntType::valueOf(10))->get());
        $this->assertEquals(9, (IntType::valueOf(9.2345))->get());
        $this->assertEquals(1239, (IntType::valueOf('1239'))->get());
        $this->assertEquals(1240, (IntType::valueOf('1239.9'))->get());
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform null to IntType.
     */
    public function testBadFromNull()
    {
        IntType::valueOf(null);
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform object to IntType.
     */
    public function testBadFromObject()
    {
        IntType::valueOf(new \stdClass());
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform resource to IntType.
     */
    public function testBadFromResource()
    {
        IntType::valueOf(tmpfile());
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform boolean to IntType.
     */
    public function testBadFromBoolean()
    {
        IntType::valueOf(false);
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform array to IntType.
     */
    public function testBadFromArray()
    {
        IntType::valueOf([]);
    }

    /**
     * Non-numeric string fails.
     *
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform string to IntType.
     */
    public function testBadFromString()
    {
        IntType::valueOf('Foo');
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform object to BooleanType.
     */
    public function testBadTransmutableBool()
    {
        (new IntType(0))->toBool();
    }

    public function testTransmutable()
    {
        $this->assertEquals(new StringType(1), (new IntType(1))->toString());
        $this->assertEquals(new IntType(10), (new IntType(10))->toInt()); //Redundant, but w/e.
        $this->assertEquals(new FloatType(1.0), (new IntType(1))->toFloat());
    }
}
