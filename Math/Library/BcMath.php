<?php

declare (strict_types = 1);

namespace Tdn\PhpTypes\Math\Library;

use Tdn\PhpTypes\Type\StringType;

/**
 * Class BcMath.
 */
class BcMath implements MathLibraryInterface
{
    /**
     * @var int
     */
    private $roundingStrategy;

    /**
     * @param int $roundingStrategy
     */
    public function __construct(int $roundingStrategy)
    {
        $this->roundingStrategy = $roundingStrategy;
    }

    /**
     * Add two arbitrary precision numbers.
     *
     * @param string $leftOperand
     * @param string $rightOperand
     * @param int    $precision
     *
     * @return string
     */
    public function add(string $leftOperand, string $rightOperand, int $precision = 0) : string
    {
        $precision = $this->getBcPrecision($leftOperand, $precision);

        return \bcadd($leftOperand, $rightOperand, $precision);
    }

    /**
     * Subtract two arbitrary precision numbers.
     *
     * @param string $leftOperand
     * @param string $rightOperand
     * @param int    $precision
     *
     * @return string
     */
    public function subtract(string $leftOperand, string $rightOperand, int $precision = 0) : string
    {
        $precision = $this->getBcPrecision($leftOperand, $precision);

        return \bcsub($leftOperand, $rightOperand, $precision);
    }

    /**
     * Multiply two arbitrary precision numbers.
     *
     * @param string $leftOperand
     * @param string $rightOperand
     * @param int    $precision
     *
     * @return string
     */
    public function multiply(string $leftOperand, string $rightOperand, int $precision = 0) : string
    {
        $precision = $this->getBcPrecision($leftOperand, $precision);

        return bcmul($leftOperand, $rightOperand, $precision);
    }

    /**
     * Divide two arbitrary precision numbers.
     *
     * @param string $leftOperand
     * @param string $rightOperand
     * @param int    $precision
     *
     * @return string
     */
    public function divide(string $leftOperand, string $rightOperand, int $precision = 0) : string
    {
        $precision = $this->getBcPrecision($leftOperand, $precision);

        return bcdiv($leftOperand, $rightOperand, $precision);
    }

    /**
     * Compare two arbitrary precision numbers.
     *
     * @param string $leftOperand
     * @param string $rightOperand
     * @param int    $precision
     *
     * @return string
     */
    public function compare(string $leftOperand, string $rightOperand, int $precision = 0) : string
    {
        if ($this->isVersionComparison($leftOperand, $rightOperand)) {
            throw new \RuntimeException('BcMath cannot do version compare.');
        }

        $precision = $this->getBcPrecision($leftOperand, $precision);

        return strval(bccomp($leftOperand, $rightOperand, $precision));
    }

    /**
     * Get modulus of an arbitrary precision number.
     *
     * @param string $operand
     * @param string $modulus
     * @param int    $precision
     *
     * @return string
     */
    public function modulus(string $operand, string $modulus, int $precision = 0) : string
    {
        if ($precision > 0) {
            throw new \RuntimeException('Precision is not supported. Use Spl::modulus, it uses fmod.');
        }

        return bcmod($operand, $modulus);
    }

    /**
     * Raise an arbitrary precision number to another.
     *
     * @param string $leftOperand
     * @param string $rightOperand
     * @param int    $precision
     *
     * @return string
     */
    public function power(string $leftOperand, string $rightOperand, int $precision = 0) : string
    {
        $precision = $this->getBcPrecision($leftOperand, $precision);

        return bcpow($leftOperand, $rightOperand, $precision);
    }

    /**
     * Get the square root of an arbitrary precision number.
     *
     * @param string $operand
     * @param int    $precision
     *
     * @return string
     */
    public function squareRoot(string $operand, int $precision = 0) : string
    {
        $bcPrecision = $this->getBcPrecision($operand, $precision) + 1;

        return (string) round(bcsqrt($operand, $bcPrecision), $precision, $this->roundingStrategy);
    }

    /**
     * Returns absolute value of operand.
     *
     * @param string $operand
     *
     * @return string
     */
    public function absolute(string $operand) : string
    {
        throw $this->createInvalidLibraryException(__FUNCTION__);
    }

    /**
     * Negates a number. Opposite of absolute/abs.
     *
     * @param string $operand
     *
     * @return string
     */
    public function negate(string $operand) : string
    {
        throw $this->createInvalidLibraryException(__FUNCTION__);
    }

    /**
     * Returns the factorial of operand.
     *
     * @param string $operand
     *
     * @return string
     */
    public function factorial(string $operand) : string
    {
        throw $this->createInvalidLibraryException(__FUNCTION__);
    }

    /**
     * Greatest common divisor.
     *
     * @param string $leftOperand
     * @param string $rightOperand
     *
     * @return string
     */
    public function gcd(string $leftOperand, string $rightOperand) : string
    {
        throw $this->createInvalidLibraryException(__FUNCTION__);
    }

    /**
     * Calculates to the nth root.
     *
     * @param string $operand
     * @param int    $nth
     *
     * @return string
     */
    public function root(string $operand, int $nth) : string
    {
        throw $this->createInvalidLibraryException(__FUNCTION__);
    }

    /**
     * Gets the next prime after operand.
     *
     * @param string $operand
     *
     * @return string
     */
    public function nextPrime(string $operand) : string
    {
        throw $this->createInvalidLibraryException(__FUNCTION__);
    }

    /**
     * @param string $operand
     * @param int    $reps
     *
     * @return bool
     */
    public function isPrime(string $operand, int $reps = 10) : bool
    {
        throw $this->createInvalidLibraryException(__FUNCTION__);
    }

    /**
     * Checks if operand is perfect square.
     *
     * @param string $operand
     * @param int    $precision
     *
     * @return bool
     */
    public function isPerfectSquare(string $operand, int $precision = 0) : bool
    {
        throw $this->createInvalidLibraryException(__FUNCTION__);
    }

    /**
     * The gamma function.
     *
     * @param string $operand
     *
     * @return string
     */
    public function gamma(string $operand) : string
    {
        throw $this->createInvalidLibraryException(__FUNCTION__);
    }

    /**
     * The log-gamma function.
     *
     * @param string $operand
     *
     * @return string
     */
    public function logGamma(string $operand) : string
    {
        throw $this->createInvalidLibraryException(__FUNCTION__);
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function supportsOperationType(string $type) : bool
    {
        //Supports both float and int.
        return true;
    }

    /**
     * @return bool
     */
    public function isEnabled() : bool
    {
        return extension_loaded('bcmath');
    }

    /**
     * Returns BCMath Precision.
     *
     * It differs from standard PHP precision in that it includes the numbers before the decimal period.
     * It always uses the left most operand to calculate precision.
     *
     * @param string $leftOperand
     * @param int    $precision
     *
     * @return int
     */
    private function getBcPrecision(string $leftOperand, int $precision = 0) : int
    {
        if ($precision > 0) {
            $operand = StringType::create($leftOperand);
            if ($operand->contains('.')) {
                return $operand->countSubstr('.') + $precision;
            }
        }

        return $precision;
    }

    /**
     * @param string $leftOperand
     * @param string $rightOperand
     *
     * @return bool
     */
    private function isVersionComparison(string $leftOperand, string $rightOperand) : bool
    {
        return (StringType::create($leftOperand)->countSubstr('.') > 1) ||
        (StringType::create($rightOperand)->countSubstr('.') > 1);
    }

    /**
     * @param string $methodName
     *
     * @return \RuntimeException
     */
    private function createInvalidLibraryException(string $methodName) : \RuntimeException
    {
        return new \RuntimeException(
            sprintf('Not a valid library for %s.', $methodName)
        );
    }
}
