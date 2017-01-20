<?php

declare(strict_types=1);

namespace Tdn\PhpTypes\Math;

use Tdn\PhpTypes\Exception\InvalidNumberException;
use Tdn\PhpTypes\Math\Library\BcMath;
use Tdn\PhpTypes\Math\Library\Gmp;
use Tdn\PhpTypes\Math\Library\MathLibraryInterface;
use Tdn\PhpTypes\Math\Library\Spl;

/**
 * Class DefaultMathLibraryAdapter.
 */
class DefaultMathAdapter extends AbstractMathAdapter implements MathAdapterInterface
{
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
        return $this->getDelegateResult(__FUNCTION__, $leftOperand, $rightOperand, $precision);
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
        return $this->getDelegateResult(__FUNCTION__, $leftOperand, $rightOperand, $precision);
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
        return $this->getDelegateResult(__FUNCTION__, $leftOperand, $rightOperand, $precision);
    }

    /**
     * Divide two arbitrary precision numbers.
     *
     * @param string $leftOperand
     * @param string $rightOperand
     * @param int    $precision
     *
     * @return string
     *
     * @throws \DivisionByZeroError
     */
    public function divide(string $leftOperand, string $rightOperand, int $precision = 0): string
    {
        if ($rightOperand == '0') {
            throw new \DivisionByZeroError();
        }

        return $this->getDelegateResult(__FUNCTION__, $leftOperand, $rightOperand, $precision);
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
        return $this->getDelegateResult(__FUNCTION__, $leftOperand, $rightOperand, $precision);
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
        return $this->getDelegateResult(__FUNCTION__, $operand, $modulus, $precision);
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
        return $this->getDelegateResult(__FUNCTION__, $leftOperand, $rightOperand, $precision);
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
        return $this->getDelegateResult(__FUNCTION__, $operand, null, $precision);
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
        return $this->getDelegateResult(__FUNCTION__, $operand);
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
        return $this->getDelegateResult(__FUNCTION__, $operand);
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
        $type = $this->getOperationType($operand);

        if ($this->isRealNumber($type, $operand)) {
            return $this->getDelegateResult(__FUNCTION__, $operand);
        }

        throw $this->createNotRealNumberException();
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
        $type = $this->getOperationType($leftOperand, $rightOperand);

        if ($this->isRealNumber($type, $leftOperand, $rightOperand)) {
            return $this->getDelegateResult(__FUNCTION__, $leftOperand, $rightOperand);
        }

        throw $this->createNotRealNumberException();
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
        $type = $this->getOperationType($operand);
        $exception = null;

        if ($this->isRealNumber($type, $operand)) {
            foreach ($this->getDelegates($type) as $library) {
                try {
                    return $library->root($operand, $nth);
                } catch (\Throwable $e) {
                    // Save last exception and try next library.
                    $exception = new \RuntimeException($e->getMessage(), $e->getCode(), $e);
                    continue;
                }
            }
        }

        throw $exception ?? $this->createNotRealNumberException();
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
        return $this->getDelegateResult(__FUNCTION__, $operand);
    }

    /**
     * @param string $operand
     * @param int    $reps
     *
     * @return bool
     */
    public function isPrime(string $operand, int $reps = 10): bool
    {
        $type = $this->getOperationType($operand);
        $exception = null;

        if ($this->getPrecision($operand) > 0 || $operand == '1') {
            return false;
        }

        if ($operand == '2') {
            return true;
        }

        foreach ($this->getDelegates($type) as $library) {
            try {
                return $library->isPrime($operand, $reps);
            } catch (\Throwable $e) {
                // Save last exception and try next library.
                $exception = new \RuntimeException($e->getMessage(), $e->getCode(), $e);
                continue;
            }
        }

        throw $exception ?? $this->createNewUnknownErrorException();
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
        return $this->getDelegateResult(__FUNCTION__, $operand, null, $precision);
    }

    /**
     * The gamma function.
     *
     * @param string $operand
     *
     * @return string
     */
    public function gamma(string $operand): string
    {
        return $this->getDelegateResult(__FUNCTION__, $operand);
    }

    /**
     * The log-gamma function.
     *
     * @param string $operand
     *
     * @return string
     */
    public function logGamma(string $operand): string
    {
        return $this->getDelegateResult(__FUNCTION__, $operand);
    }

    /**
     * @return MathLibraryInterface[]
     */
    protected function getDefaultDelegates(): array
    {
        //Array is sorted in order of preference. Override in child class if so desired.
        return [
            'bcmath' => new BcMath($this->getRoundingStrategy()),
            'gmp' => new Gmp(),
            'spl' => new Spl($this->getRoundingStrategy()),
        ];
    }

    /**
     * @return InvalidNumberException
     */
    private function createNotRealNumberException(): InvalidNumberException
    {
        return new InvalidNumberException('Arguments must be real numbers.');
    }
}
