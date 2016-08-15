<?php

declare (strict_types = 1);

namespace Tdn\PhpTypes\Math;

use Tdn\PhpTypes\Exception\DivisionByZeroException;
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
     * {@inheritdoc}
     */
    public function add(string $leftOperand, string $rightOperand, int $precision = 0) : string
    {
        return $this->getAdapterOperationResult('add', $leftOperand, $rightOperand, $precision);
    }

    /**
     * {@inheritdoc}
     */
    public function subtract(string $leftOperand, string $rightOperand, int $precision = 0) : string
    {
        return $this->getAdapterOperationResult('subtract', $leftOperand, $rightOperand, $precision);
    }

    /**
     * {@inheritdoc}
     */
    public function multiply(string $leftOperand, string $rightOperand, int $precision = 0) : string
    {
        return $this->getAdapterOperationResult('multiply', $leftOperand, $rightOperand, $precision);
    }

    /**
     * {@inheritdoc}
     *
     * @throws DivisionByZeroException when dividing by zero.
     */
    public function divide(string $leftOperand, string $rightOperand, int $precision = 0) : string
    {
        if ($rightOperand == '0') {
            throw new DivisionByZeroException('Cannot divide by zero.');
        }

        return $this->getAdapterOperationResult('divide', $leftOperand, $rightOperand, $precision);

    }

    /**
     * {@inheritdoc}
     */
    public function compare(string $leftOperand, string $rightOperand, int $precision = 0) : string
    {
        return $this->getAdapterOperationResult('compare', $leftOperand, $rightOperand, $precision);
    }

    /**
     * {@inheritdoc}
     */
    public function modulus(string $operand, string $modulus, int $precision = 0) : string
    {
        return $this->getAdapterOperationResult('modulus', $operand, $modulus, $precision);
    }

    /**
     * {@inheritdoc}
     */
    public function power(string $leftOperand, string $rightOperand, int $precision = 0) : string
    {
        return $this->getAdapterOperationResult('power', $leftOperand, $rightOperand, $precision);
    }

    /**
     * {@inheritdoc}
     */
    public function squareRoot(string $operand, int $precision = 0) : string
    {
        return $this->getAdapterOperationResult('squareRoot', $operand, null, $precision);
    }

    /**
     * {@inheritdoc}
     */
    public function absolute(string $operand) : string
    {
        return $this->getAdapterOperationResult('absolute', $operand);
    }

    /**
     * {@inheritdoc}
     */
    public function negate(string $operand) : string
    {
        return $this->getAdapterOperationResult('negate', $operand);
    }

    /**
     * {@inheritdoc}
     */
    public function factorial(string $operand) : string
    {
        $type = $this->getOperationType($operand);
        $exception = null;

        if ($this->isRealNumber($type, $operand)) {
            foreach ($this->getLibraryForOperation($type) as $library) {
                try {
                    return $library->factorial($operand);
                } catch (\RuntimeException $e) {
                    $exception = $e;

                    continue;
                }
            }
        }

        throw ($exception ?? $this->createNewUnknownErrorException());
    }

    /**
     * {@inheritdoc}
     */
    public function gcd(string $leftOperand, string $rightOperand) : string
    {
        $type = $this->getOperationType($leftOperand, $rightOperand);
        $exception = null;

        if ($this->isRealNumber($type, $leftOperand, $rightOperand)) {
            foreach ($this->getLibraryForOperation($type) as $library) {
                try {
                    return $library->gcd($leftOperand, $rightOperand);
                } catch (\RuntimeException $e) {
                    $exception = $e;

                    continue;
                }
            }
        }

        throw ($exception ?? $this->createNewUnknownErrorException());
    }

    /**
     * {@inheritdoc}
     */
    public function root(string $operand, int $nth) : string
    {
        $type = $this->getOperationType($operand);
        $exception = null;

        if ($this->isRealNumber($type, $operand)) {
            foreach ($this->getLibraryForOperation($type) as $library) {
                try {
                    return $library->root($operand, $nth);
                } catch (\RuntimeException $e) {
                    $exception = $e;

                    continue;
                }
            }
        }

        throw ($exception ?? $this->createNewUnknownErrorException());
    }

    /**
     * {@inheritdoc}
     */
    public function nextPrime(string $operand) : string
    {
        return $this->getAdapterOperationResult('nextPrime', $operand);
    }

    /**
     * {@inheritdoc}
     */
    public function isPrime(string $operand, int $reps = 10) : bool
    {
        $type = $this->getOperationType($operand);
        $exception = null;

        if ($this->getPrecision($operand) > 0 || $operand == '1') {
            return false;
        }

        if ($operand == '2') {
            return true;
        }

        foreach ($this->getLibraryForOperation($type) as $library) {
            try {
                return $library->isPrime($operand, $reps);
            } catch (\RuntimeException $e) {
                $exception = $e;

                continue;
            }
        }

        throw ($exception ?? $this->createNewUnknownErrorException());
    }

    /**
     * {@inheritdoc}
     */
    public function isPerfectSquare(string $operand, int $precision = 0) : bool
    {
        return $this->getAdapterOperationResult('isPerfectSquare', $operand, null, $precision);
    }

    /**
     * @param int $roundingStrategy
     *
     * @return MathLibraryInterface[]
     */
    protected function getSupportedMathLibraries(int $roundingStrategy) : array
    {
        //Array is sorted in order of preference.
        return [
            'bcmath' => new BcMath($roundingStrategy),
            'gmp' => new Gmp(),
            'spl' => new Spl($roundingStrategy)
        ];
    }

    /**
     * @param callable $operation
     * @param string $leftOperand
     * @param string $rightOperand
     * @param int $precision
     * @return mixed
     */
    private function getAdapterOperationResult(
        callable $operation,
        string $leftOperand,
        string $rightOperand = null,
        int $precision = null
    ) {
        $type = $this->getOperationType($leftOperand, $rightOperand);
        $exception = null;

        foreach ($this->getLibraryForOperation($type) as $library) {
            try {
                if ($precision !== null) {
                    if ($rightOperand !== null) {
                        return $library->$operation($leftOperand, $rightOperand, $precision);
                    }

                    return $library->$operation($leftOperand, $precision);
                }

                if ($rightOperand !== null) {
                    return $library->$operation($leftOperand, $rightOperand);
                }

                return $library->$operation($leftOperand);
            } catch (\RuntimeException $e) {
                $exception = $e;

                continue;
            }
        }

        throw ($exception ?? $this->createNewUnknownErrorException());
    }

    /**
     * @return \RuntimeException
     */
    private function createNewUnknownErrorException()
    {
        return new \RuntimeException('Unknown error.');
    }
}
