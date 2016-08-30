<?php

namespace Tdn\PhpTypes\Tests\Math;

use Tdn\PhpTypes\Exception\DivisionByZeroException;
use Tdn\PhpTypes\Exception\InvalidNumberException;
use Tdn\PhpTypes\Math\DefaultMathAdapter;
use Tdn\PhpTypes\Math\Library\BcMath;
use Tdn\PhpTypes\Math\Library\Gmp;
use Tdn\PhpTypes\Math\Library\Spl;
use Tdn\PhpTypes\Math\MathAdapterInterface;
use Tdn\PhpTypes\Math\NumberValidatorInterface;

/**
 * Class DefaultMathAdapterTest.
 */
class DefaultMathAdapterTest extends \PHPUnit_Framework_TestCase
{
    private $numberValidator;

    /**
     * @var MathAdapterInterface
     */
    private $mathAdapter;

    /**
     * Create objects.
     */
    protected function setUp()
    {
        $this->numberValidator = $this->getMock(NumberValidatorInterface::class);
        $this->numberValidator
            ->expects($this->any())
            ->method('isValid')
            ->willReturn(true)
        ;

        $this->mathAdapter = new DefaultMathAdapter($this->numberValidator);
    }

    /**
     * @expectedException \OutOfBoundsException
     * @expectedExceptionMessageRegExp /Unsupported rounding strategy. Please refer to PHP's documentation on rounding.$/
     */
    public function testBadRoundingStrategy()
    {
        new DefaultMathAdapter($this->numberValidator, null, 5);
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidNumberException
     * @expectedExceptionMessageRegExp /Invalid number: Foo$/
     */
    public function testInvalidLeftOperator()
    {
        (new DefaultMathAdapter())->add('Foo', 9);
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidNumberException
     * @expectedExceptionMessageRegExp /Invalid number: Bar$/
     */
    public function testInvalidRightOperator()
    {
        (new DefaultMathAdapter())->add(1, 'Bar');
    }

    public function testPrecision()
    {
        $this->assertEquals(2, $this->mathAdapter->getPrecision('4.23'));
        $this->assertEquals(6, $this->mathAdapter->getPrecision('6.356548'));
        $this->assertEquals(0, $this->mathAdapter->getPrecision('4'));
    }

    public function testAdd()
    {
        $this->assertEquals('4', $this->mathAdapter->add(2, 2));
        $this->assertEquals('4.4', $this->mathAdapter->add(2.2, 2.2, 1));
    }

    public function testSubtract()
    {
        $this->assertEquals('0', $this->mathAdapter->subtract(2, 2));
        $this->assertEquals('0.1', $this->mathAdapter->subtract(2.3, 2.2, 1));
    }

    public function testMultiply()
    {
        $this->assertEquals('4', $this->mathAdapter->multiply(2, 2));
        $this->assertEquals('4', $this->mathAdapter->multiply(2.2, 2.2)); //No Precision
        $this->assertEquals('4.84', $this->mathAdapter->multiply(2.2, 2.2, 2)); //2 Precision points
    }

    /**
     * @expectedException \DivisionByZeroError
     */
    public function testBadDivideByZero()
    {
        $this->mathAdapter->divide(10, 0);
    }

    public function testDivide()
    {
        $this->assertEquals('1', $this->mathAdapter->divide(2, 2));
        $this->assertEquals('2.0', $this->mathAdapter->divide(2.2, 1.1, 1));
    }

    public function testCompare()
    {
        $this->assertEquals('1', $this->mathAdapter->compare('1.4', '1.04', 2));
        $this->assertEquals('-1', $this->mathAdapter->compare('1', '10'));
        $this->assertEquals('0', $this->mathAdapter->compare('3', '3'));

        //Version comp
        $this->assertEquals('-1', $this->mathAdapter->compare('0.90.01', '0.91.04', 5));
    }

    public function testModulus()
    {
        $this->assertEquals('5', $this->mathAdapter->modulus('5', '10'));
        $this->assertEquals('5.5', $this->mathAdapter->modulus('5.5', '10', 1));
    }

    public function testPower()
    {
        $this->assertEquals('9765625', $this->mathAdapter->power('5', '10'));
        $this->assertEquals('766217865.41', $this->mathAdapter->power('5.5', '12', 2));
    }

    public function testSquareRoot()
    {
        $this->assertEquals('3', $this->mathAdapter->squareRoot(9));
        $this->assertEquals('7.0278', $this->mathAdapter->squareRoot('49.39', 4));
    }

    public function testAbsolute()
    {
        $this->assertEquals('9', $this->mathAdapter->absolute('-9'));
        $this->assertEquals('49.39', $this->mathAdapter->absolute('-49.39'));
    }

    public function testNegate()
    {
        $this->assertEquals('9', $this->mathAdapter->negate('-9'));
        $this->assertEquals('-9', $this->mathAdapter->negate('9'));
        $this->assertEquals('-49.39', $this->mathAdapter->negate('49.39'));
    }

    public function testFactorial()
    {
        $this->assertEquals('3628800', $this->mathAdapter->factorial('10'));
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidNumberException
     */
    public function testBadFactorial()
    {
        $this->mathAdapter->factorial('4.4');
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidNumberException
     * @expectedExceptionMessage Arguments must be real numbers.
     */
    public function testBadGcd()
    {
        $this->mathAdapter->gcd('-5', '0');
        $this->mathAdapter->gcd(10.5, 5);
    }

    public function testGcd()
    {
        $this->assertEquals('10', $this->mathAdapter->gcd('10', '50'));
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidNumberException
     * @expectedExceptionMessage Arguments must be real numbers.
     */
    public function testBadRoot()
    {
        $this->mathAdapter->root('-5', 2);
    }

    public function testRoot()
    {
        $this->assertEquals('3', $this->mathAdapter->root('32', 3));
    }

    public function testIsPrime()
    {
        $this->assertFalse($this->mathAdapter->isPrime(1));
        $this->assertTrue($this->mathAdapter->isPrime(2));
        $this->assertTrue($this->mathAdapter->isPrime(5));
    }

    public function testNextPrime()
    {
        $this->assertEquals('7', $this->mathAdapter->nextPrime('5'));
    }

    public function testIsPerfectSquare()
    {
        $this->assertTrue($this->mathAdapter->isPerfectSquare(1));
        $this->assertTrue($this->mathAdapter->isPerfectSquare(4));
        $this->assertTrue($this->mathAdapter->isPerfectSquare(9));
        $this->assertFalse($this->mathAdapter->isPerfectSquare(3));
        $this->assertFalse($this->mathAdapter->isPerfectSquare(5));
        $this->assertFalse($this->mathAdapter->isPerfectSquare(26));
    }

    /**
     * @expectedException \Tdn\PhpTypes\Exception\InvalidNumberException
     *
     * @expectedExceptionMessage Invalid number: Foo
     */
    public function testBadPrecision()
    {
        (new DefaultMathAdapter())->getPrecision('Foo');
    }
}
