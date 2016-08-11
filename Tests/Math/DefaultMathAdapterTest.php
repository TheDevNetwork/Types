<?php

namespace Tdn\PhpTypes\Tests\Math;

use Tdn\PhpTypes\Exception\DivisionByZeroException;
use Tdn\PhpTypes\Exception\InvalidNumberException;
use Tdn\PhpTypes\Math\DefaultMathAdapter;
use Tdn\PhpTypes\Math\NumberValidatorInterface;

/**
 * Class DefaultMathAdapterTest.
 */
class DefaultMathAdapterTest extends \PHPUnit_Framework_TestCase
{
    private $numberValidator;

    /**
     * @var DefaultMathAdapter
     */
    private $gmpAdapter;

    /**
     * @var DefaultMathAdapter
     */
    private $bcMathAdapter;

    /**
     * @var DefaultMathAdapter
     */
    private $splAdapter;

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

        $this->gmpAdapter = new DefaultMathAdapter(
            $this->numberValidator,
            PHP_ROUND_HALF_UP,
            DefaultMathAdapter::EXT_GMP
        );

        $this->bcMathAdapter = new DefaultMathAdapter(
            $this->numberValidator,
            PHP_ROUND_HALF_UP,
            DefaultMathAdapter::EXT_BCMATH
        );

        $this->splAdapter = new DefaultMathAdapter(
            $this->numberValidator,
            PHP_ROUND_HALF_UP,
            DefaultMathAdapter::EXT_SPL
        );
    }

    /**
     * @expectedException \OutOfBoundsException
     * @expectedExceptionMessageRegExp /Unsupported rounding strategy. Please refer to PHP's documentation.$/
     */
    public function testBadRoundingStrategy()
    {
        new DefaultMathAdapter($this->numberValidator, 5);
    }

    /**
     * @expectedException \OutOfBoundsException
     * @expectedExceptionMessageRegExp /Unsupported extension foo. Only the following extensions are supported: (.*)$/
     */
    public function testBadExtension()
    {
        new DefaultMathAdapter($this->numberValidator, PHP_ROUND_HALF_UP, 'foo');
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
        $this->assertEquals(2, $this->splAdapter->getPrecision(4.23));
        $this->assertEquals(6, $this->splAdapter->getPrecision('6.356548'));
        $this->assertEquals(0, $this->splAdapter->getPrecision(4));
    }

    public function testAdd()
    {
        $this->assertEquals('4', $this->gmpAdapter->add(2, 2));
        $this->assertEquals('4', $this->bcMathAdapter->add(2, 2));
        $this->assertEquals('4', $this->splAdapter->add(2, 2));
        $this->assertEquals('4.4', $this->bcMathAdapter->add(2.2, 2.2, 1));
        $this->assertEquals('4.4', $this->splAdapter->add(2.2, 2.2, 1));
        try {
            $this->gmpAdapter->add(2.2, 2.2);
            $this->fail('GMP Should throw exception.');
        } catch (\Throwable $e) {
            $this->assertEquals(
                'gmp_add(): Unable to convert variable to GMP - string is not an integer',
                $e->getMessage()
            );
        }
    }

    public function testSubtract()
    {
        $this->assertEquals('0', $this->gmpAdapter->subtract(2, 2));
        $this->assertEquals('0', $this->bcMathAdapter->subtract(2, 2));
        $this->assertEquals('0', $this->splAdapter->subtract(2, 2));
        $this->assertEquals('0.1', $this->bcMathAdapter->subtract(2.3, 2.2, 1));
        $this->assertEquals('0.1', $this->splAdapter->subtract(2.3, 2.2, 1));

        try {
            $this->gmpAdapter->subtract(2.3, 2.2);
            $this->fail('GMP Should throw exception.');
        } catch (\Throwable $e) {
            $this->assertEquals(
                'gmp_sub(): Unable to convert variable to GMP - string is not an integer',
                $e->getMessage()
            );
        }
    }

    public function testMultiply()
    {
        $this->assertEquals('4', $this->gmpAdapter->multiply(2, 2));
        $this->assertEquals('4', $this->bcMathAdapter->multiply(2, 2));
        $this->assertEquals('4', $this->splAdapter->multiply(2, 2));
        $this->assertEquals('4', $this->bcMathAdapter->multiply(2.2, 2.2)); //No Precision
        $this->assertEquals('4', $this->splAdapter->multiply(2.2, 2.2)); //No precision, rounds up
        $this->assertEquals('4.84', $this->bcMathAdapter->multiply(2.2, 2.2, 2)); //2 Precision points
        $this->assertEquals('4.84', $this->splAdapter->multiply(2.2, 2.2, 2)); //2 Precision points

        try {
            $this->gmpAdapter->multiply(2.3, 2.2);
            $this->fail('GMP Should throw exception.');
        } catch (\Throwable $e) {
            $this->assertEquals(
                'gmp_mul(): Unable to convert variable to GMP - string is not an integer',
                $e->getMessage()
            );
        }
    }

    public function testDivide()
    {
        $this->assertEquals('1', $this->gmpAdapter->divide(2, 2));
        $this->assertEquals('1', $this->bcMathAdapter->divide(2, 2));
        $this->assertEquals('1', $this->splAdapter->divide(2, 2));
        $this->assertEquals('2.0', $this->bcMathAdapter->divide(2.2, 1.1, 1));
        $this->assertEquals('2.0', $this->splAdapter->divide(2.2, 1.1, 1));

        try {
            $this->gmpAdapter->divide(2.2, 2.2);
            $this->fail('GMP Should throw exception.');
        } catch (\Throwable $e) {
            $this->assertEquals(
                'gmp_div(): Unable to convert variable to GMP - string is not an integer',
                $e->getMessage()
            );
        }

        try {
            $this->splAdapter->divide(10, 0);
            $this->fail('Should create DivisionByZeroException');
        } catch (DivisionByZeroException $e) {
            $this->assertEquals('Cannot divide by zero.', $e->getMessage());
        }
    }

    public function testCompare()
    {
        $this->assertEquals('1', $this->gmpAdapter->compare(8, 7));
        $this->assertEquals('1', $this->bcMathAdapter->compare('1.4', '1.04', 2));
        $this->assertEquals('1', $this->splAdapter->compare('1.4', '1.04', 2));

        try {
            $this->gmpAdapter->compare('1.4', '1.04');
            $this->fail('GMP Should throw exception.');
        } catch (\Throwable $e) {
            $this->assertEquals(
                'gmp_cmp(): Unable to convert variable to GMP - string is not an integer',
                $e->getMessage()
            );
        }

        try {
            $this->gmpAdapter->compare('1.4.5', '1.04.3');
            $this->fail('GMP Should throw exception.');
        } catch (\Throwable $e) {
            $this->assertEquals(
                'gmp_cmp(): Unable to convert variable to GMP - string is not an integer',
                $e->getMessage()
            );
        }

        $this->assertEquals('-1', $this->gmpAdapter->compare('1', '10'));
        $this->assertEquals('-1', $this->bcMathAdapter->compare('0.90.01', '0.91.04', 5));
        $this->assertEquals('-1', $this->splAdapter->compare('0.90.01', '0.91.04', 5));
        // a = b
        $this->assertEquals('0', $this->gmpAdapter->compare('3', '3'));
        $this->assertEquals('0', $this->bcMathAdapter->compare('3', '3'));
        $this->assertEquals('0', $this->splAdapter->compare('3', '3'));
    }

    public function testModulus()
    {
        $this->assertEquals('5', $this->gmpAdapter->modulus(5, '10'));
        $this->assertEquals('5', $this->bcMathAdapter->modulus(5, 10));
        $this->assertEquals('5', $this->splAdapter->modulus('5', '10'));
        $this->assertEquals('5.5', $this->splAdapter->modulus('5.5', '10', 1));
        $this->assertEquals('5.5', $this->bcMathAdapter->modulus(5.5, '10', 1)); //Should fall back to spl.

        try {
            $this->gmpAdapter->modulus(5.5, 10);
            $this->fail('GMP Should throw exception.');
        } catch (\Throwable $e) {
            $this->assertEquals(
                'gmp_mod(): Unable to convert variable to GMP - string is not an integer',
                $e->getMessage()
            );
        }
    }

    public function testPower()
    {
        $this->assertEquals('9765625', $this->gmpAdapter->power(5, '10'));
        $this->assertEquals('9765625', $this->bcMathAdapter->power(5, 10));
        $this->assertEquals('9765625', $this->splAdapter->power('5', '10'));
        $this->assertEquals('766217865.41', $this->bcMathAdapter->power(5.5, 12, 2));
        $this->assertEquals('766217865.41', $this->splAdapter->power('5.5', '12', 2));

        try {
            $this->gmpAdapter->power(5.5, '12', 2);
            $this->fail('GMP Should throw exception.');
        } catch (\Throwable $e) {
            $this->assertEquals(
                'gmp_pow(): Unable to convert variable to GMP - string is not an integer',
                $e->getMessage()
            );
        }
    }

    public function testSquareRoot()
    {
        $this->assertEquals('3', $this->gmpAdapter->squareRoot(9));
        $this->assertEquals('3', $this->bcMathAdapter->squareRoot(9));
        $this->assertEquals('3', $this->splAdapter->squareRoot(9));
        $this->assertEquals('7.0278', $this->bcMathAdapter->squareRoot('49.39', 4));
        $this->assertEquals('7.0278', $this->splAdapter->squareRoot(49.39, 4));
        $this->assertEquals('7.03', $this->bcMathAdapter->squareRoot('49.39', 2));
        $this->assertEquals('7.03', $this->splAdapter->squareRoot(49.39, 2));

        try {
            $this->gmpAdapter->squareRoot(49.39, 2);
            $this->fail('Should throw exception.');
        } catch (\Throwable $e) {
            $this->assertEquals(
                'gmp_sqrt(): Unable to convert variable to GMP - string is not an integer',
                $e->getMessage()
            );
        }
    }

    public function testAbsolute()
    {
        $this->assertEquals('9', $this->gmpAdapter->absolute(-9));
        $this->assertEquals('9', $this->bcMathAdapter->absolute('-9'));
        $this->assertEquals('9', $this->splAdapter->absolute(-9));

        $this->assertEquals('49.39', $this->bcMathAdapter->absolute('-49.39'));
        $this->assertEquals('49.39', $this->splAdapter->absolute(-49.39));

        try {
            $this->gmpAdapter->absolute(-49.39);
            $this->fail('Should throw exception.');
        } catch (\Throwable $e) {
            $this->assertEquals(
                'gmp_abs(): Unable to convert variable to GMP - string is not an integer',
                $e->getMessage()
            );
        }
    }

    public function testNegate()
    {
        $this->assertEquals('9', $this->gmpAdapter->negate(-9));
        $this->assertEquals('9', $this->bcMathAdapter->negate('-9'));
        $this->assertEquals('9', $this->splAdapter->negate(-9));

        $this->assertEquals('-9', $this->gmpAdapter->negate(9));
        $this->assertEquals('-9', $this->bcMathAdapter->negate('9'));
        $this->assertEquals('-9', $this->splAdapter->negate(9));

        $this->assertEquals('-49.39', $this->bcMathAdapter->negate('49.39'));
        $this->assertEquals('-49.39', $this->splAdapter->negate(49.39));

        try {
            $this->gmpAdapter->negate(49.39);
            $this->fail('GMP Should throw exception.');
        } catch (\Throwable $e) {
            $this->assertEquals(
                'gmp_neg(): Unable to convert variable to GMP - string is not an integer',
                $e->getMessage()
            );
        }
    }

    public function testFactorial()
    {
        $this->assertEquals('3628800', $this->gmpAdapter->factorial(10));
        $this->assertEquals('3628800', $this->bcMathAdapter->factorial('10'));
        $this->assertEquals('3628800', $this->splAdapter->factorial(10));

        try {
            $this->gmpAdapter->factorial(10.5);
            $this->fail('GMP Float: Should throw exception');
        } catch (InvalidNumberException $e) {
            $this->assertEquals('Factorial argument must be real number.', $e->getMessage());
        }

        try {
            $this->bcMathAdapter->factorial('10.5');
            $this->fail('BC Float: Should throw exception');
        } catch (InvalidNumberException $e) {
            $this->assertEquals('Factorial argument must be real number.', $e->getMessage());
        }

        try {
            $this->splAdapter->factorial('10.5');
            $this->fail('SPL Float: Should throw exception');
        } catch (InvalidNumberException $e) {
            $this->assertEquals('Factorial argument must be real number.', $e->getMessage());
        }

        try {
            $this->splAdapter->factorial('-5');
            $this->fail('SPL Negative: Should throw exception');
        } catch (InvalidNumberException $e) {
            $this->assertEquals('Factorial argument must be real number.', $e->getMessage());
        }
    }

    public function testGcd()
    {
        $this->assertEquals('10', $this->gmpAdapter->gcd(10, 50));
        $this->assertEquals('10', $this->bcMathAdapter->gcd('10', '50'));
        $this->assertEquals('10', $this->splAdapter->gcd('10', 50));

        try {
            $this->gmpAdapter->gcd(10.5, 5);
            $this->fail('GMP: Should throw exception');
        } catch (InvalidNumberException $e) {
            $this->assertEquals('GCD argument must be real number.', $e->getMessage());
        }

        try {
            $this->splAdapter->gcd('-5', '0');
            $this->fail('SPL: Should throw exception');
        } catch (InvalidNumberException $e) {
            $this->assertEquals('GCD argument must be real number.', $e->getMessage());
        }
    }

    public function testRoot()
    {
        $this->assertEquals('3', $this->gmpAdapter->root('32', 3));

        try {
            $this->splAdapter->root('-5', 2);
            $this->fail('Negative number should throw exception.');
        } catch (InvalidNumberException $e) {
            $this->assertEquals('Root argument must be real number.', $e->getMessage());
        }

        try {
            $this->bcMathAdapter->root('32', 3);
            $this->fail('BC MATH: Should Throw Exception');
        } catch (\RuntimeException $e) {
            $this->assertEquals('No valid operators for root^n.', $e->getMessage());
        }

        try {
            $this->splAdapter->root('32', 3);
            $this->fail('SPL: Should Throw Exception');
        } catch (\RuntimeException $e) {
            $this->assertEquals('No valid operators for root^n.', $e->getMessage());
        }
    }

    public function testIsPrime()
    {
        $this->assertFalse($this->splAdapter->isPrime(1));
        $this->assertTrue($this->splAdapter->isPrime(2));
        $this->assertTrue($this->splAdapter->isPrime(5));
        $this->assertTrue($this->gmpAdapter->isPrime(5));

        try {
            $this->bcMathAdapter->isPrime(5);
            $this->fail('BC Math no valid operator.');
        } catch (\RuntimeException $e) {
            $this->assertEquals('Not a valid operator for isPrime.', $e->getMessage());
        }
    }

    public function testNextPrime()
    {
        $this->assertEquals('7', $this->splAdapter->nextPrime('5'));
        $this->assertEquals('7', $this->gmpAdapter->nextPrime('5'));

        try {
            $this->bcMathAdapter->nextPrime('5');
            $this->fail('BC MATH: Should Throw Exception');
        } catch (\RuntimeException $e) {
            $this->assertEquals('Not a valid operator for nextPrime.', $e->getMessage());
        }
    }

    public function testIsPerfectSquare()
    {
        $this->assertTrue($this->gmpAdapter->isPerfectSquare(1));
        $this->assertTrue($this->gmpAdapter->isPerfectSquare(4));
        $this->assertTrue($this->gmpAdapter->isPerfectSquare(9));
        $this->assertFalse($this->gmpAdapter->isPerfectSquare(3));
        $this->assertFalse($this->gmpAdapter->isPerfectSquare(5));
        $this->assertFalse($this->gmpAdapter->isPerfectSquare(26));

        $this->assertTrue($this->splAdapter->isPerfectSquare(1));
        $this->assertTrue($this->splAdapter->isPerfectSquare(1));
        $this->assertTrue($this->splAdapter->isPerfectSquare(4));
        $this->assertTrue($this->splAdapter->isPerfectSquare(9));
        $this->assertFalse($this->splAdapter->isPerfectSquare(3));
        $this->assertFalse($this->splAdapter->isPerfectSquare(5));
        $this->assertFalse($this->splAdapter->isPerfectSquare(26));

        try {
            $this->bcMathAdapter->isPerfectSquare(1);
            $this->fail('BC Math should fail perfect square.');
        } catch (\RuntimeException $e) {
            $this->assertEquals('Not a valid operator for isPerfectSquare.', $e->getMessage());
        }
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
