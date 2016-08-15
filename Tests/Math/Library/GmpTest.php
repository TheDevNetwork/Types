<?php

namespace Tdn\PhpTypes\Tests\Math\Library;

use Tdn\PhpTypes\Exception\InvalidNumberException;
use Tdn\PhpTypes\Math\Library\Gmp;

class GmpTest extends AbstractMathLibraryTest
{
    protected function setUp()
    {
        return $this->mathLibrary = new Gmp();
    }

    /**
     * @expectedException InvalidNumberException
     * @expectedExceptionMessage GCD argument must be real number.
     */
    public function testBadGcd()
    {
        $this->mathLibrary->gcd(10.5, 5);
    }
}
