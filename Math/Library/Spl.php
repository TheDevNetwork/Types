<?php

declare (strict_types = 1);

namespace Tdn\PhpTypes\Math\Library;

/**
 * Class Spl.
 */
class Spl implements MathLibraryInterface
{
    /**
     * @var int
     */
    private $roundingStrategy;

    /**
     * @param int $roundingStrategy a PHP_ROUND_HALF_* integer.
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
     * @param int $precision
     *
     * @return string
     */
    public function add(string $leftOperand, string $rightOperand, int $precision = 0) : string
    {
        return (string) ($this->isIntOperation($precision) ? (intval($leftOperand) + intval($rightOperand)) :
            round(floatval($leftOperand) + floatval($rightOperand), $precision, $this->roundingStrategy));
    }

    /**
     * Subtract two arbitrary precision numbers.
     *
     * @param string $leftOperand
     * @param string $rightOperand
     * @param int $precision
     *
     * @return string
     */
    public function subtract(string $leftOperand, string $rightOperand, int $precision = 0) : string
    {
        return (string) ($this->isIntOperation($precision) ? (intval($leftOperand) - intval($rightOperand)) :
            round($leftOperand - $rightOperand, $precision, $this->roundingStrategy));
    }

    /**
     * Multiply two arbitrary precision numbers.
     *
     * @param string $leftOperand
     * @param string $rightOperand
     * @param int $precision
     *
     * @return string
     */
    public function multiply(string $leftOperand, string $rightOperand, int $precision = 0) : string
    {
        return (string) ($this->isIntOperation($precision) ? (intval($leftOperand) * intval($rightOperand)) :
            round($leftOperand * $rightOperand, ($precision ?? 0), $this->roundingStrategy));
    }

    /**
     * Divide two arbitrary precision numbers.
     *
     * @param string $leftOperand
     * @param string $rightOperand
     * @param int $precision
     *
     * @return string
     */
    public function divide(string $leftOperand, string $rightOperand, int $precision = 0) : string
    {
        return (string) ($this->isIntOperation($precision) ? (intval($leftOperand) / intval($rightOperand)) :
            round($leftOperand / $rightOperand, $precision, $this->roundingStrategy));
    }

    /**
     * Compare two arbitrary precision numbers.
     *
     * @param string $leftOperand
     * @param string $rightOperand
     * @param int $precision
     *
     * @return string
     */
    public function compare(string $leftOperand, string $rightOperand, int $precision = 0) : string
    {
        return strval($leftOperand <=> $rightOperand);
    }

    /**
     * Get modulus of an arbitrary precision number.
     *
     * @param string $operand
     * @param string $modulus
     * @param int $precision
     *
     * @return string
     */
    public function modulus(string $operand, string $modulus, int $precision = 0) : string
    {
        return (string) round(
            fmod(
                floatval($operand),
                floatval($modulus)
            ),
            ($precision ?? 0),
            $this->roundingStrategy
        );
    }

    /**
     * Raise an arbitrary precision number to another.
     *
     * @param string $leftOperand
     * @param string $rightOperand
     * @param int $precision
     *
     * @return string
     */
    public function power(string $leftOperand, string $rightOperand, int $precision = 0) : string
    {
        return (string) round(
            pow(
                floatval($leftOperand),
                floatval($rightOperand)
            ),
            ($precision ?? 0),
            $this->roundingStrategy
        );
    }

    /**
     * Get the square root of an arbitrary precision number.
     *
     * @param string $operand
     * @param int $precision
     *
     * @return string
     */
    public function squareRoot(string $operand, int $precision = 0) : string
    {
        return (string) round(sqrt(floatval($operand)), ($precision ?? 0), $this->roundingStrategy);
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
        return (string) abs($operand);
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
        $factorial = function (string $num) use (&$factorial) {
            if ($num < 2) {
                return 1;
            }

            return $factorial(strval($num - 1)) * $num;
        };

        return (string) $factorial($operand);
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
        $gcd = function (string $a, string $b) use (&$gcd) {
            return $b > .01 ? $gcd($b, strval(fmod(floatval($a), floatval($b)))) : $a;
        };

        return (string) $gcd($leftOperand, $rightOperand);
    }

    /**
     * Calculates to the nth root.
     *
     * @param string $operand
     * @param int $nth
     *
     * @return string
     */
    public function root(string $operand, int $nth) : string
    {
        //Implement something later.
        throw new \RuntimeException('Not a valid library for root^n.');
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
        $operand = (intval($operand) + 1);
        for ($i = $operand;; ++$i) {
            if ($this->isPrime(strval($i))) {
                break;
            }
        }

        return (string) $i;
    }

    /**
     * @param string $operand
     * @param int $reps
     *
     * @return bool
     */
    public function isPrime(string $operand, int $reps = 10) : bool
    {
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
     * @param int|null $precision
     *
     * @return bool
     */
    public function isPerfectSquare(string $operand, int $precision = 0) : bool
    {
        $candidate = $this->squareRoot($operand, $precision + 1);

        return $candidate == intval($candidate);
    }

    /**
     * @return bool
     */
    public function isEnabled() : bool
    {
        return true;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function supportsOperationType(string $type) : bool
    {
        // Supports both float and int.
        return true;
    }

    /**
     * Ensures that an operation is meant to be an integer operation, float operation otherwise.
     *
     * @param int|null $precision
     *
     * @return bool
     */
    private function isIntOperation(int $precision = 0) : bool
    {
        return $precision === null || $precision === 0;
    }
}
