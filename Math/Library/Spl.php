<?php

declare(strict_types=1);

namespace Tdn\PhpTypes\Math\Library;

use Tdn\PhpTypes\Exception\InvalidNumberException;
use Tdn\PhpTypes\Math\DefaultMathAdapter;
use Tdn\PhpTypes\Type\StringType;

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
     * @param int $roundingStrategy a PHP_ROUND_HALF_* integer
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
    public function add(string $leftOperand, string $rightOperand, int $precision = 0): string
    {
        return (string) ($this->isIntOperation($precision) ? (intval($leftOperand) + intval($rightOperand)) :
            round(floatval($leftOperand) + floatval($rightOperand), $precision, $this->roundingStrategy));
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
    public function subtract(string $leftOperand, string $rightOperand, int $precision = 0): string
    {
        return (string) ($this->isIntOperation($precision) ? (intval($leftOperand) - intval($rightOperand)) :
            round($leftOperand - $rightOperand, $precision, $this->roundingStrategy));
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
    public function multiply(string $leftOperand, string $rightOperand, int $precision = 0): string
    {
        return (string) ($this->isIntOperation($precision) ? (intval($leftOperand) * intval($rightOperand)) :
            round($leftOperand * $rightOperand, ($precision ?? 0), $this->roundingStrategy));
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
    public function divide(string $leftOperand, string $rightOperand, int $precision = 0): string
    {
        return (string) ($this->isIntOperation($precision) ? (intval($leftOperand) / intval($rightOperand)) :
            round($leftOperand / $rightOperand, $precision, $this->roundingStrategy));
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
    public function compare(string $leftOperand, string $rightOperand, int $precision = 0): string
    {
        return strval($leftOperand <=> $rightOperand);
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
    public function modulus(string $operand, string $modulus, int $precision = 0): string
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
     * @param int    $precision
     *
     * @return string
     */
    public function power(string $leftOperand, string $rightOperand, int $precision = 0): string
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
     * @param int    $precision
     *
     * @return string
     */
    public function squareRoot(string $operand, int $precision = 0): string
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
    public function absolute(string $operand): string
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
    public function negate(string $operand): string
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
    public function factorial(string $operand): string
    {
        if (StringType::create($operand)->contains('.')) {
            ++$operand;

            return $this->gamma((string) $operand);
        }

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
    public function gcd(string $leftOperand, string $rightOperand): string
    {
        $gcd = function (string $a, string $b) use (&$gcd) {
            return $b ? $gcd($b, strval($a % $b)) : $a;
        };

        $exponent = $this->getSmallestDecimalPlaceCount($leftOperand, $rightOperand);

        return (string) (
            $gcd(
                strval($leftOperand * (pow(10, $exponent))),
                strval($rightOperand * (pow(10, $exponent)))
            ) / pow(10, $exponent)
        );
    }

    /**
     * Calculates to the nth root.
     *
     * @param string $operand
     * @param int    $nth
     *
     * @return string
     */
    public function root(string $operand, int $nth): string
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
    public function nextPrime(string $operand): string
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
     * @param int    $reps
     *
     * @return bool
     */
    public function isPrime(string $operand, int $reps = 10): bool
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
     * @param int    $precision
     *
     * @return bool
     */
    public function isPerfectSquare(string $operand, int $precision = 0): bool
    {
        $candidate = $this->squareRoot($operand, $precision + 1);

        return $candidate == intval($candidate);
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return true;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function supportsOperationType(string $type): bool
    {
        // Supports both float and int.
        return true;
    }

    /**
     * @param string $operand
     *
     * @return string
     */
    public function gamma(string $operand): string
    {
        if ($operand <= 0.0) {
            throw new InvalidNumberException('Operand must be a positive number.');
        }

        // Euler's gamma constant
        $gamma = 0.577215664901532860606512090;

        if ($operand < 0.001) {
            return strval(1.0 / ($operand * (1.0 + $gamma * $operand)));
        }

        if ($operand < 12.0) {
            $y = $operand;
            $n = 0;
            $lessThanOne = ($y < 1.0);
            if ($lessThanOne) {
                $y += 1.0;
            } else {
                // will use n later
                $n = floor($y) - 1;
                $y -= $n;
            }

            $p = [
                -1.71618513886549492533811E+0,
                2.47656508055759199108314E+1,
                -3.79804256470945635097577E+2,
                6.29331155312818442661052E+2,
                8.66966202790413211295064E+2,
                -3.14512729688483675254357E+4,
                -3.61444134186911729807069E+4,
                6.64561438202405440627855E+4,
            ];

            $q = [
                -3.08402300119738975254353E+1,
                3.15350626979604161529144E+2,
                -1.01515636749021914166146E+3,
                -3.10777167157231109440444E+3,
                2.25381184209801510330112E+4,
                4.75584627752788110767815E+3,
                -1.34659959864969306392456E+5,
                -1.15132259675553483497211E+5,
            ];

            $num = 0.0;
            $den = 1.0;
            $z = $y - 1;

            for ($i = 0; $i < 8; ++$i) {
                $num = ($num + $p[$i]) * $z;
                $den = $den * $z + $q[$i];
            }

            $result = $num / $den + 1.0;

            if ($lessThanOne) {
                $result /= ($y - 1.0);
            } else {
                for ($i = 0; $i < $n; ++$i) {
                    $result *= $y++;
                }
            }

            return (string) $result;
        }

        if ($operand > 171.624) {
            throw new \RuntimeException('Number too large.');
        }

        return (string) exp(floatval($this->logGamma((string) $operand)));
    }

    /**
     * @param string $operand
     *
     * @return string
     */
    public function logGamma(string $operand): string
    {
        if ($operand <= 0.0) {
            throw new InvalidNumberException('Operand must be a positive number.');
        }

        if ($operand < 12.0) {
            return (string) log(abs($this->gamma((string) $operand)));
        }

        $c = [
            1.0 / 12.0,
            -1.0 / 360.0,
            1.0 / 1260.0,
            -1.0 / 1680.0,
            1.0 / 1188.0,
            -691.0 / 360360.0,
            1.0 / 156.0,
            -3617.0 / 122400.0,
        ];

        $z = 1.0 / ($operand * $operand);
        $sum = $c[7];
        for ($i = 6; $i >= 0; --$i) {
            $sum *= $z;
            $sum += $c[$i];
        }

        $series = $sum / $operand;
        $halfLogTwoPi = 0.91893853320467274178032973640562;
        $logGamma = (floatval($operand) - 0.5) * log(floatval($operand)) - $operand + $halfLogTwoPi + $series;

        return (string) $logGamma;
    }

    /**
     * Figures out the smallest number of decimal places between the two numbers and returns that count.
     * Eg. (1.005, 2.4) => 1, (1.005, 2.5399) => 3.
     *
     * @param string $leftOperand
     * @param string $rightOperand
     *
     * @return int
     */
    private function getSmallestDecimalPlaceCount(string $leftOperand, string $rightOperand): int
    {
        $leftPrecision = DefaultMathAdapter::getNumberPrecision($leftOperand);
        $rightPrecision = DefaultMathAdapter::getNumberPrecision($rightOperand);

        return $leftPrecision < $rightPrecision ? $leftPrecision : $rightPrecision;
    }

    /**
     * Ensures that an operation is meant to be an integer operation, float operation otherwise.
     *
     * @param int $precision
     *
     * @return bool
     */
    private function isIntOperation(int $precision = 0): bool
    {
        return $precision === null || $precision === 0;
    }
}
