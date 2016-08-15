<?php

namespace Tdn\PhpTypes\Tests\Math\Library;

use Tdn\PhpTypes\Math\Library\Spl;

class SplTest extends AbstractMathLibraryTest
{
    protected function setUp()
    {
        $this->mathLibrary = new Spl(PHP_ROUND_HALF_UP);
    }

    public function testCompare()
    {
        //Can compare versions
        $this->assertEquals('1', $this->mathLibrary->compare('1.30.5', '1.29.99', 5));
        $this->assertEquals('1', $this->mathLibrary->compare('1.105.02', '1.049.9', 5));
        parent::testCompare();
    }

    public function testNextPrime()
    {
        $this->assertEquals('7', $this->mathLibrary->nextPrime('5.5'));
    }

    public function testGcd()
    {
        $this->assertEquals('10', $this->mathLibrary->gcd('10.5', '50.75'));
        parent::testGcd();
    }

    public function testFactorial()
    {
        $this->assertEquals('10', $this->mathLibrary->factorial('4.4'));
    }
}
