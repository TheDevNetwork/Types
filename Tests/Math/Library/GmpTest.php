<?php

namespace Tdn\PhpTypes\Tests\Math\Library;

use Tdn\PhpTypes\Math\Library\Gmp;

/**
 * Class GmpTest.
 */
class GmpTest extends AbstractMathLibraryTest
{
    protected function setUp()
    {
        return $this->mathLibrary = new Gmp();
    }

    public function testGcd()
    {
        try {
            $this->mathLibrary->gcd(10.5, 5);
            $this->fail('Not a real number.');
        } catch (\Throwable $e) {
            $this->assertEquals(
                'gmp_gcd(): Unable to convert variable to GMP - string is not an integer',
                $e->getMessage()
            );
        }

        parent::testGcd();
    }

    public function testRoot()
    {
        try {
            $this->mathLibrary->root('5.5', 5);
            $this->fail('Not a real number.');
        } catch (\Throwable $e) {
            $this->assertEquals(
                'gmp_init(): Unable to convert variable to GMP - string is not an integer',
                $e->getMessage()
            );
        }

        parent::testRoot();
    }
}
