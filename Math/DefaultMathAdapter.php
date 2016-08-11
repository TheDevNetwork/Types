<?php

declare (strict_types = 1);

namespace Tdn\PhpTypes\Math;

use Tdn\PhpTypes\Exception\DivisionByZeroException;
use Tdn\PhpTypes\Exception\InvalidNumberException;
use Tdn\PhpTypes\Type\StringType;

/**
 * Class DefaultMathLibraryAdapter.
 */
class DefaultMathAdapter extends AbstractMathAdapter implements MathAdapterInterface
{
    /**
     * @param string   $type
     * @param int|null $precision
     *
     * @return bool
     */
    private function isIntOperation(string $type, int $precision = null) : bool
    {
        return $type === self::TYPE_INT || ($precision === null || $precision === 0);
    }

    /**
     * Add two arbitrary precision numbers.
     *
     * @param string   $leftOperand
     * @param string   $rightOperand
     * @param int|null $precision
     *
     * @return string
     */
    public function add(string $leftOperand, string $rightOperand, int $precision = null) : string
    {
        $type = $this->getOperationType($leftOperand, $rightOperand);

        if ($this->isPrecisionLibraryOperation()) {
            return $this->getPrecisionResult('bcadd', $leftOperand, $rightOperand, $precision);
        }

        if ($this->isIntegerLibraryOperation($type)) {
            return $this->getNonPrecisionResult('gmp_add', $leftOperand, $rightOperand);
        }

        return strval(
            $this->isIntOperation($type, $precision) ? (intval($leftOperand) + intval($rightOperand)) :
                round(floatval($leftOperand) + floatval($rightOperand), $precision, $this->getRoundingStrategy())
        );
    }

    /**
     * Subtract two arbitrary precision numbers.
     *
     * @param string   $leftOperand
     * @param string   $rightOperand
     * @param int|null $precision
     *
     * @return string
     */
    public function subtract(string $leftOperand, string $rightOperand, int $precision = null) : string
    {
        $type = $this->getOperationType($leftOperand, $rightOperand);

        if ($this->isPrecisionLibraryOperation()) {
            return $this->getPrecisionResult('bcsub', $leftOperand, $rightOperand, $precision);
        }

        if ($this->isIntegerLibraryOperation($type)) {
            return $this->getNonPrecisionResult('gmp_sub', $leftOperand, $rightOperand);
        }

        return strval(
            $this->isIntOperation($type, $precision) ? (intval($leftOperand) - intval($rightOperand)) :
                round($leftOperand - $rightOperand, $precision, $this->getRoundingStrategy())
        );
    }

    /**
     * Multiply two arbitrary precision numbers.
     *
     * @param string   $leftOperand
     * @param string   $rightOperand
     * @param int|null $precision
     *
     * @return string
     */
    public function multiply(string $leftOperand, string $rightOperand, int $precision = null) : string
    {
        $type = $this->getOperationType($leftOperand, $rightOperand);

        if ($this->isPrecisionLibraryOperation()) {
            return $this->getPrecisionResult('bcmul', $leftOperand, $rightOperand, $precision);
        }

        if ($this->isIntegerLibraryOperation($type)) {
            return $this->getNonPrecisionResult('gmp_mul', $leftOperand, $rightOperand);
        }

        return strval(
            $this->isIntOperation($type, $precision) ? (intval($leftOperand) * intval($rightOperand)) :
                round($leftOperand * $rightOperand, ($precision ?? 0), $this->getRoundingStrategy())
        );
    }

    /**
     * Divide two arbitrary precision numbers.
     *
     * @param string   $leftOperand
     * @param string   $rightOperand
     * @param int|null $precision
     *
     * @throws DivisionByZeroException when dividing by zero.
     *
     * @return string
     */
    public function divide(string $leftOperand, string $rightOperand, int $precision = null) : string
    {
        if ($rightOperand == '0') {
            throw new DivisionByZeroException('Cannot divide by zero.');
        }

        $type = $this->getOperationType($leftOperand, $rightOperand);

        if ($this->isPrecisionLibraryOperation()) {
            return $this->getPrecisionResult('bcdiv', $leftOperand, $rightOperand, $precision);
        }

        if ($this->isIntegerLibraryOperation($type)) {
            return $this->getNonPrecisionResult('gmp_div', $leftOperand, $rightOperand);
        }

        return strval(
            $this->isIntOperation($type, $precision) ? (intval($leftOperand) / intval($rightOperand)) :
                round($leftOperand / $rightOperand, $precision, $this->getRoundingStrategy())
        );
    }

    /**
     * Compare two arbitrary precision numbers.
     *
     * @param string   $leftOperand
     * @param string   $rightOperand
     * @param int|null $precision
     *
     * @return string
     */
    public function compare(string $leftOperand, string $rightOperand, int $precision = null) : string
    {
        //Check for special versions
        $type = $this->getOperationType($leftOperand, $rightOperand);
        if ($this->isPrecisionLibraryOperation() && (StringType::create($leftOperand)->countSubstr('.') == 1 ||
            StringType::create($rightOperand)->countSubstr('.') == 1)) {
            return $this->getPrecisionResult('bccomp', $leftOperand, $rightOperand, $precision);
        }

        if ($this->isIntegerLibraryOperation($type)) {
            return $this->getNonPrecisionResult('gmp_cmp', $leftOperand, $rightOperand);
        }

        return strval($leftOperand <=> $rightOperand);
    }

    /**
     * Get modulus of an arbitrary precision number.
     *
     * @param string   $operand
     * @param string   $modulus
     * @param int|null $precision
     *
     * @return string
     */
    public function modulus(string $operand, string $modulus, int $precision = null) : string
    {
        $type = $this->getOperationType($operand, $modulus);

        if ($this->isPrecisionLibraryOperation() && ($precision === null || $precision === 0)) {
            return $this->getPrecisionResult('bcmod', $operand, $modulus);
        }

        if ($this->isIntegerLibraryOperation($type)) {
            return $this->getNonPrecisionResult('gmp_mod', $operand, $modulus);
        }

        return strval(
            round(
                fmod(
                    floatval($operand),
                    floatval($modulus)
                ),
                ($precision ?? 0),
                $this->getRoundingStrategy()
            )
        );
    }

    /**
     * Raise an arbitrary precision number to another.
     *
     * @param string   $leftOperand
     * @param string   $rightOperand
     * @param int|null $precision
     *
     * @return string
     */
    public function power(string $leftOperand, string $rightOperand, int $precision = null) : string
    {
        $type = $this->getOperationType($leftOperand, $rightOperand);

        if ($this->isPrecisionLibraryOperation()) {
            return $this->getPrecisionResult('bcpow', $leftOperand, $rightOperand, $precision);
        }

        if ($this->isIntegerLibraryOperation($type)) {
            return $this->getNonPrecisionResult('gmp_pow', $leftOperand, intval($rightOperand));
        }

        return strval(
            round(
                pow(
                    floatval($leftOperand),
                    floatval($rightOperand)
                ),
                ($precision ?? 0),
                $this->getRoundingStrategy()
            )
        );
    }

    /**
     * Get the square root of an arbitrary precision number.
     *
     * @param string   $operand
     * @param int|null $precision
     *
     * @return string
     */
    public function squareRoot(string $operand, int $precision = null) : string
    {
        $type = $this->getOperationType($operand);

        if ($this->isPrecisionLibraryOperation()) {
            return strval(
                round(
                    $this->getPrecisionResult('bcsqrt', $operand, null, $precision + 1),
                    ($precision ?? 0),
                    $this->getRoundingStrategy()
                )
            );
        }

        if ($this->isIntegerLibraryOperation($type)) {
            return $this->getNonPrecisionResult('gmp_sqrt', $operand);
        }

        return strval(round(sqrt(floatval($operand)), ($precision ?? 0), $this->getRoundingStrategy()));
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
        $type = $this->getOperationType($operand);

        if ($this->isIntegerLibraryOperation($type)) {
            return $this->getNonPrecisionResult('gmp_abs', $operand);
        }

        return strval(abs($operand));
    }

    /**
     * Negates a number.
     *
     * @param string $operand
     *
     * @return string
     */
    public function negate(string $operand) : string
    {
        $type = $this->getOperationType($operand);

        if ($this->isIntegerLibraryOperation($type)) {
            return $this->getNonPrecisionResult('gmp_neg', $operand);
        }

        return strval($operand * -1);
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
        $type = $this->getOperationType($operand);

        if ($type !== self::TYPE_INT || 0 > $operand) {
            throw new InvalidNumberException('Factorial argument must be real number.');
        }

        if ($this->isIntegerLibraryOperation($type)) {
            return $this->getNonPrecisionResult('gmp_fact', $operand);
        }

        $factorial = function (string $num) use (&$factorial) {
            if ($num < 2) {
                return 1;
            }

            return $factorial(strval($num - 1)) * $num;
        };

        return strval($factorial($operand));
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
        $type = $this->getOperationType($leftOperand, $rightOperand);

        if ($type !== self::TYPE_INT || 0 > $leftOperand || 0 > $rightOperand) {
            throw new InvalidNumberException('GCD argument must be real number.');
        }

        if ($this->isIntegerLibraryOperation($type)) {
            return $this->getNonPrecisionResult('gmp_gcd', $leftOperand, $rightOperand);
        }

        $gcd = function (string $a, string $b) use (&$gcd) {
            return $b > .01 ? $gcd($b, strval(fmod(floatval($a), floatval($b)))) : $a;
        };

        return strval($gcd($leftOperand, $rightOperand));
    }

    /**
     * Calculates to the nth root.
     *
     * @param string $operand
     * @param int    $nth
     *
     * @throws \RuntimeException When no valid library is installed to perform the operation.
     *
     * @return string
     */
    public function root(string $operand, int $nth) : string
    {
        $type = $this->getOperationType($operand);

        if ($type !== self::TYPE_INT || $operand < 0 || $nth < 0) {
            throw new InvalidNumberException('Root argument must be real number.');
        }

        if ($this->isIntegerLibraryOperation($type)) {
            return $this->getNonPrecisionResult('gmp_root', $operand, $nth);
        }

        //Implement something later.
        throw new \RuntimeException('No valid operators for root^n.');
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
        if ($this->getForcedExtension() == self::EXT_BCMATH) {
            throw new \RuntimeException('Not a valid operator for nextPrime.');
        }

        $type = $this->getOperationType($operand);

        if ($this->isIntegerLibraryOperation($type)) {
            return $this->getNonPrecisionResult('gmp_nextprime', $operand);
        }

        $operand = (intval($operand) + 1);
        for ($i = $operand;; ++$i) {
            if ($this->isPrime(strval($i))) {
                break;
            }
        }

        return strval($i);
    }

    /**
     * @param string $operand
     * @param int    $reps
     *
     * @return bool
     */
    public function isPrime(string $operand, int $reps = 10) : bool
    {
        if ($this->getForcedExtension() == self::EXT_BCMATH) {
            throw new \RuntimeException('Not a valid operator for isPrime.');
        }

        if ($this->getPrecision($operand) > 0 || $operand == '1') {
            return false;
        }

        if ($operand == '2') {
            return true;
        }

        $type = $this->getOperationType($operand);

        if ($this->isIntegerLibraryOperation($type)) {
            return boolval($this->getNonPrecisionResult('gmp_prob_prime', $operand, $reps));
        }

        $x = floor(sqrt(floatval($operand)));
        for ($i = 2; $i <= $x; ++$i) {
            if (($operand % $i) == 0) {
                break;
            }
        }

        return ($x == ($i - 1)) ? true : false;
    }

    /**
     * Checks if operand is perfect square.
     *
     * @param string $operand
     *
     * @return bool
     */
    public function isPerfectSquare(string $operand) : bool
    {
        if ($this->getForcedExtension() == self::EXT_BCMATH) {
            throw new \RuntimeException('Not a valid operator for isPerfectSquare.');
        }

        $type = $this->getOperationType($operand);

        if ($this->isIntegerLibraryOperation($type)) {
            return boolval($this->getNonPrecisionResult('gmp_perfect_square', $operand));
        }

        $candidate = $this->squareRoot($operand, $this->getPrecision($operand) + 1);

        return intval($candidate) == $candidate;
    }
}
