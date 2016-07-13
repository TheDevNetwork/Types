<?php

namespace Tdn\PhpTypes\Tests\Math\Library;

use Tdn\PhpTypes\Math\Library\BcMath;

/**
 * Class BcMathTest.
 */
class BcMathTest extends AbstractPrecisionMathLibraryTest
{
    /**
     * @var BcMath
     */
    protected $mathLibrary;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->mathLibrary = new BcMath(PHP_ROUND_HALF_UP);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage BcMath cannot do version compare.
     */
    public function testNoVersionCompare()
    {
        $this->mathLibrary->compare('0.90.01', '0.91.04', 5);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Precision is not supported. Use Spl::modulus, it uses fmod.
     */
    public function testBadModulus()
    {
        $this->mathLibrary->modulus('5.5', '10', 1);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Not a valid library for absolute.
     */
    public function testAbsolute()
    {
        $this->mathLibrary->absolute('-9');
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Not a valid library for negate.
     */
    public function testNegate()
    {
        $this->mathLibrary->negate('-9');
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Not a valid library for factorial.
     */
    public function testFactorial()
    {
        $this->mathLibrary->factorial('10');
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Not a valid library for gcd.
     */
    public function testGcd()
    {
        $this->mathLibrary->gcd('10', '50');
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Not a valid library for root.
     */
    public function testRoot()
    {
        $this->mathLibrary->root('32', 3);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage  Not a valid library for isPrime.
     */
    public function testIsPrime()
    {
        $this->mathLibrary->isPrime(5);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Not a valid library for nextPrime.
     */
    public function testNextPrime()
    {
        $this->mathLibrary->nextPrime('5');
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Not a valid library for isPerfectSquare.
     */
    public function testIsPerfectSquare()
    {
        $this->mathLibrary->isPerfectSquare(1);
    }
}
