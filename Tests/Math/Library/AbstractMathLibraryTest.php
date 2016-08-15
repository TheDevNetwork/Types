<?php

namespace Tdn\PhpTypes\Tests\Math\Library;

use Tdn\PhpTypes\Math\Library\MathLibraryInterface;

/**
 * Class AbstractMathLibraryTest.
 */
abstract class AbstractMathLibraryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MathLibraryInterface
     */
    protected $mathLibrary;

    public function testAdd()
    {
        $this->assertEquals('4', $this->mathLibrary->add(2, 2));
    }

    public function testSubtract()
    {
        $this->assertEquals('0', $this->mathLibrary->subtract(2, 2));
    }

    public function testMultiply()
    {
        $this->assertEquals('4', $this->mathLibrary->multiply(2, 2));
    }

    public function testDivide()
    {
        $this->assertEquals('1', $this->mathLibrary->divide(2, 2));
    }

    public function testCompare()
    {
        $this->assertEquals('0', $this->mathLibrary->compare('3', '3'));
    }

    public function testModulus()
    {
        $this->assertEquals('5', $this->mathLibrary->modulus(5, 10));
    }

    public function testPower()
    {
        $this->assertEquals('9765625', $this->mathLibrary->power(5, 10));
    }

    public function testSquareRoot()
    {
        $this->assertEquals('3', $this->mathLibrary->squareRoot(9));
    }

    public function testAbsolute()
    {
        $this->assertEquals('9', $this->mathLibrary->absolute('-9'));
    }

    public function testNegate()
    {
        $this->assertEquals('9', $this->mathLibrary->negate('-9'));
        $this->assertEquals('-9', $this->mathLibrary->negate('9'));
    }

    public function testFactorial()
    {
        $this->assertEquals('3628800', $this->mathLibrary->factorial('10'));
    }

    public function testGcd()
    {
        $this->assertEquals('10', $this->mathLibrary->gcd('10', '50'));
    }

    public function testRoot()
    {
        $this->assertEquals('3', $this->mathLibrary->root('32', 3));
    }

    public function testIsPrime()
    {
        $this->assertTrue($this->mathLibrary->isPrime(5));
    }

    public function testNextPrime()
    {
        $this->assertEquals('7', $this->mathLibrary->nextPrime('5'));
    }

    public function testIsPerfectSquare()
    {
        $this->assertTrue($this->mathLibrary->isPerfectSquare(1));
        $this->assertTrue($this->mathLibrary->isPerfectSquare(4));
        $this->assertTrue($this->mathLibrary->isPerfectSquare(9));
        $this->assertFalse($this->mathLibrary->isPerfectSquare(3));
        $this->assertFalse($this->mathLibrary->isPerfectSquare(5));
        $this->assertFalse($this->mathLibrary->isPerfectSquare(26));
    }
}
