<?php

namespace Tdn\PhpTypes\Tests\Type;

use Tdn\PhpTypes\Type\FloatType;
use Tdn\PhpTypes\Type\IntType;
use Tdn\PhpTypes\Type\StringType;
use Tdn\PhpTypes\Type\Type;

/**
 * Class FloatTypeTest.
 */
class FloatTypeTest extends AbstractTypeTest
{
    public function testBox()
    {
        /* @var FloatType $float */
        FloatType::box($float, new FloatType(1.0));
        $this->assertTrue(($float instanceof FloatType));
        $this->assertEquals(1.0, $float());

        $float = 493.29;
        $this->assertTrue(($float instanceof FloatType));
        $this->assertEquals(493.29, $float());

        $float = new FloatType(493.29);
        $this->assertTrue(($float instanceof FloatType));
        $this->assertEquals(493.29, $float());

        FloatType::box($otherFloat, 948.399);
        $this->assertTrue(($otherFloat instanceof FloatType));
        $this->assertEquals(948.399, $otherFloat());
    }

    public function testUnbox()
    {
        /* @var FloatType $float */
        FloatType::box($float, new FloatType(1.0));
        $this->assertEquals('1.0', $float(Type::STRING));
        $float = 493.6;
        $this->assertEquals('493.6', $float(Type::STRING));
        $this->assertEquals(493.6, $float());
        $this->assertEquals(493.6, $float(Type::FLOAT));
        $this->assertEquals(493, $float(Type::INT));
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTypeCastException
     * @expectedExceptionMessage Could not cast FloatType to array.
     */
    public function testUnboxArrayFail()
    {
        /* @var FloatType $float */
        FloatType::box($float, new FloatType(1.0));
        $float(Type::ARRAY);
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTypeCastException
     * @expectedExceptionMessage Could not cast FloatType to bool.
     */
    public function testUnboxBoolFail()
    {
        /* @var FloatType $float */
        FloatType::box($float, new FloatType(1.0));
        $float(Type::BOOL);
    }

    /**
     * @expectedException \TypeError
     * @expectedExceptionMessageRegExp /Argument ([0-9]+) passed to (.*) must be of the type (.*), string given.*$/
     */
    public function testBoxBreak()
    {
        FloatType::box($float, 0.0);
        $float = '1.0';
        $this->fail($float);
    }

    /**
     * @expectedException \TypeError
     * @expectedExceptionMessageRegExp /Argument ([0-9]+) passed to (.*) must be of the type float, none given.*$/
     */
    public function testBadBoxCall()
    {
        FloatType::box($float);
    }

    public function testMultiply()
    {
        $this->assertEquals(new FloatType(38.4), (new FloatType(6.4))->multiplyBy(new IntType(6)));
        $this->assertEquals(new FloatType(38.1, 2), (new FloatType(6.35))->multiplyBy(6));
        $this->assertEquals(34.036, (new FloatType(5.36, 3))->multiplyBy(new FloatType(6.35))->get());
        $this->assertEquals(34.036, (new FloatType(5.36, 3))->multiplyBy(6.35)->get());
    }

    public function testAdd()
    {
        $this->assertEquals(1.1, (new FloatType(0.7))->plus(new FloatType(0.4))->get());
        $this->assertEquals(1.1, (new FloatType(0.7))->plus(0.4)->get());
    }

    public function testSubstract()
    {
        $this->assertEquals(new FloatType(9.6), (new FloatType(10, 1))->minus(new FloatType(0.4)));
        $this->assertEquals(new FloatType(9.6), (new FloatType(10.0, 1))->minus(0.4));
        $this->assertEquals(12.6, (new FloatType(17.6))->minus(new IntType(5))->get());
        $this->assertEquals(12.6, (new FloatType(17.6))->minus(5)->get());
    }

    public function testDivideBy()
    {
        $this->assertEquals(229.78, (new FloatType(459.56))->divideBy(new IntType(2))->get());
        $this->assertEquals(229.78, (new FloatType(919.12))->divideBy(4)->get());
        $this->assertEquals(8.843, (new FloatType(66.32, 3))->divideBy(7.5)->get());
    }

    public function testAbsolute()
    {
        $this->assertEquals(30.39, (new FloatType(30.39))->absolute()->get());
        $this->assertEquals(30.39, (new FloatType(-30.39))->absolute()->get());
    }

    public function testCompare()
    {
        $this->assertEquals(new FloatType(1), (new FloatType(5))->compare(4));
        $this->assertEquals(new FloatType(1), (new FloatType(6))->compare(3));
        $this->assertEquals(new FloatType(1.0, 1), (new FloatType(0.7, 1))->compare(0.3));
    }

    public function testModulus()
    {
        $this->assertEquals(new FloatType(5.5), (new FloatType(5.5))->modulus(10));
    }

    public function testPower()
    {
        $this->assertEquals(new FloatType(766217865.41), (new FloatType(5.5, 2))->power(12));
        $this->assertEquals(new FloatType(766217865.4), (new FloatType(5.5))->power(12));
    }

    public function testSquareRoot()
    {
        $this->assertEquals(new FloatType(3), (new FloatType(9))->squareRoot());
        $this->assertEquals(new FloatType(7.03), (new FloatType(49.39))->squareRoot());
        $this->assertEquals(new FloatType(7.02780193233), (new FloatType(49.39, 11))->squareRoot());
    }

    public function testNegate()
    {
        $this->assertEquals(new FloatType(-49.39), (new FloatType(49.39))->negate());
        $this->assertEquals(new FloatType(49.39), (new FloatType(-49.39))->negate());
    }

    public function testFactorial()
    {
        $this->assertEquals(new FloatType(3628800), (new FloatType(10))->factorial());
    }

    public function testGcd()
    {
        $this->assertEquals(new FloatType(10), (new FloatType(10))->gcd(50));
    }

    public function testRoot()
    {
        $this->assertEquals(new FloatType(3), (new FloatType(32))->root(3));
    }

    public function testNextPrime()
    {
        $this->assertEquals(new FloatType(7), (new FloatType(5))->getNextPrime());
        $this->assertEquals(new FloatType(5), (new FloatType(4.5))->getNextPrime());
    }

    public function testIsPrime()
    {
        $this->assertTrue((new FloatType(5))->isPrime()->get());
        $this->assertFalse((new FloatType(4.5))->isPrime()->get());
        $this->assertFalse((new FloatType(5.5))->isPrime()->get());
    }

    public function testIsPerfectSquare()
    {
        $this->assertTrue((new FloatType(4))->isPerfectSquare()->get());
        $this->assertFalse((new FloatType(4.5))->isPerfectSquare()->get());
    }

    public function testType()
    {
        $this->assertTrue(is_float((new FloatType(10.0))->get()));
    }

    public function testValue()
    {
        $this->assertEquals(5.5, (new FloatType(5.5))->get());
    }

    public function testFrom()
    {
        $this->assertEquals(10.0, (FloatType::valueOf(new IntType(10)))->get());
        $this->assertEquals(9.49, (FloatType::valueOf(9.49))->get());
        $this->assertEquals(7, (FloatType::valueOf(7))->get());
        $this->assertEquals(1239.369, (FloatType::valueOf('1239.369'))->get());
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform null to FloatType.
     */
    public function testBadFromNull()
    {
        FloatType::valueOf(null);
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform object to FloatType.
     */
    public function testBadFromObject()
    {
        FloatType::valueOf(new \stdClass());
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform resource to FloatType.
     */
    public function testBadFromResource()
    {
        FloatType::valueOf(tmpfile());
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform boolean to FloatType.
     */
    public function testBadFromBoolean()
    {
        FloatType::valueOf(false);
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform array to FloatType.
     */
    public function testBadFromArray()
    {
        FloatType::valueOf([]);
    }

    /**
     * Non-numeric string fails.
     *
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform string to FloatType.
     */
    public function testBadFromString()
    {
        FloatType::valueOf('Foo');
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidTransformationException
     * @expectedExceptionMessage Could not transform object to BooleanType.
     */
    public function testBadTransmutableBool()
    {
        (new FloatType(0))->toBool();
    }

    public function testTransmutable()
    {
        $this->assertEquals(new StringType(99.9), (new FloatType(99.9))->toString());
        $this->assertEquals(new IntType(11), (new FloatType(10.9))->toInt());
        $this->assertEquals(new FloatType(1.00), (new FloatType(1, 2))->toFloat());
    }
}
