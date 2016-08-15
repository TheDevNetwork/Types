<?php

namespace Tdn\PhpTypes\Tests\Math\Library;

use Tdn\PhpTypes\Exception\InvalidNumberException;
use Tdn\PhpTypes\Math\Library\MathLibraryInterface;

/**
 * Class AbstractMathLibraryTest.
 */
abstract class AbstractPrecisionMathLibraryTest extends AbstractMathLibraryTest
{
    /**
     * @var MathLibraryInterface
     */
    protected $mathLibrary;

    public function testAdd()
    {
        $this->assertEquals('4.4', $this->mathLibrary->add('2.2', '2.2', 1));
        parent::testAdd();
    }

    public function testSubtract()
    {
        $this->assertEquals('0.1', $this->mathLibrary->subtract('2.3', '2.2', 1));
        parent::testSubtract();
    }

    public function testMultiply()
    {
        $this->assertEquals('4', $this->mathLibrary->multiply('2.2', '2.2')); //No Precision
        $this->assertEquals('4.84', $this->mathLibrary->multiply('2.2', '2.2', 2)); //2 Precision points
        parent::testMultiply();
    }

    public function testDivide()
    {
        $this->assertEquals('2.0', $this->mathLibrary->divide('2.2', '1.1', 1));
        parent::testDivide();
    }

    public function testCompare()
    {
        $this->assertEquals('1', $this->mathLibrary->compare('1.4', '1.04', 2));
        parent::testCompare();
    }

    public function testModulus()
    {
        $this->assertEquals('5.5', $this->mathLibrary->modulus('5.5', '10', 1));
        parent::testModulus();
    }

    public function testPower()
    {
        $this->assertEquals('766217865.41', $this->mathLibrary->power('5.5', '12', 2));
        parent::testPower();
    }

    public function testSquareRoot()
    {
        $this->assertEquals('7.0278', $this->mathLibrary->squareRoot('49.39', 4));
        $this->assertEquals('7.03', $this->mathLibrary->squareRoot('49.39', 2));
        $this->assertEquals('37.939', $this->mathLibrary->squareRoot('1439.39', 3));
        parent::testSquareRoot();
    }

    public function testAbsolute()
    {
        $this->assertEquals('49.39', $this->mathLibrary->absolute('-49.39'));
        parent::testAbsolute();
    }

    public function testNegate()
    {
        $this->assertEquals('-49.39', $this->mathLibrary->negate('49.39'));
        $this->assertEquals('49.39', $this->mathLibrary->negate('-49.39'));
        parent::testNegate();
    }
}
