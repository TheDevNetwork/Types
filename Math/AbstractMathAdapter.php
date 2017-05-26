<?php

namespace Tdn\PhpTypes\Math;

use Tdn\PhpTypes\Exception\InvalidNumberException;
use Tdn\PhpTypes\Math\Library\MathLibraryInterface;
use Tdn\PhpTypes\Type\StringType;

/**
 * Class AbstractMathAdapter.
 */
abstract class AbstractMathAdapter implements MathAdapterInterface
{
    /**
     * @var NumberValidatorInterface
     */
    private $validator;

    /**
     * @var array|MathLibraryInterface[]
     */
    private $delegates = [];

    /**
     * @var int
     */
    private $roundingStrategy;

    /**
     * @param NumberValidatorInterface|null $validator
     * @param MathLibraryInterface|null     $delegate
     * @param int                           $roundingStrategy
     *
     * @throws \OutOfBoundsException when a rounding strategy is passed as argument and not supported
     */
    public function __construct(
        NumberValidatorInterface $validator = null,
        MathLibraryInterface $delegate = null,
        int $roundingStrategy = PHP_ROUND_HALF_UP
    ) {
        if ($roundingStrategy !== null && !in_array($roundingStrategy, static::getSupportedRoundingStrategies())) {
            throw new \OutOfBoundsException(
                'Unsupported rounding strategy. Please refer to PHP\'s documentation on rounding.'
            );
        }

        $this->validator = $validator ?? new DefaultNumberValidator();
        $this->roundingStrategy = $roundingStrategy;
        $this->delegates = $delegate ? [$delegate] : $this->getDefaultDelegates();
    }

    /**
     * @param $number
     *
     * @return int
     */
    public static function getNumberPrecision($number): int
    {
        $string = StringType::valueOf($number);
        if ($string->contains('.')) {
            return $string->substr(($string->indexOf('.') + 1), $string->length())->count();
        }

        return 0;
    }

    /**
     * @return int
     */
    public function getRoundingStrategy(): int
    {
        return $this->roundingStrategy;
    }

    /**
     * Returns the precision of number.
     *
     * @param string $number
     *
     * @return int
     */
    public function getPrecision($number): int
    {
        if ($this->validator->isValid($number)) {
            return static::getNumberPrecision($number);
        }

        throw new InvalidNumberException(sprintf('Invalid number: %s', ($number ?: gettype($number))));
    }

    /**
     * @return MathLibraryInterface[]
     */
    abstract protected function getDefaultDelegates(): array;

    /**
     * Iterates through libraries to operate on.
     *
     * @param string $type
     *
     * @return \Generator|MathLibraryInterface[]
     */
    protected function getDelegates(string $type): \Generator
    {
        foreach ($this->delegates as $library) {
            if ($library->isEnabled() && $library->supportsOperationType($type)) {
                yield $library;
            }
        }
    }

    /**
     * @param string $type
     * @param string $leftOperand
     * @param string $rightOperand
     *
     * @return bool
     */
    protected function isRealNumber(string $type, string $leftOperand, string $rightOperand = '0')
    {
        return !($type !== self::TYPE_INT || $leftOperand < 0 || $rightOperand < 0);
    }

    /**
     * Ensures operands are valid and returns the operation type.
     *
     * @param string      $a
     * @param string|null $b
     *
     * @throws InvalidNumberException when an operand is not a valid number
     *
     * @return string
     */
    protected function getOperationType(string $a, string $b = null): string
    {
        $getType = function ($v, $previousType = null) {
            $previousType = $previousType ?? self::TYPE_INT;

            return (strpos($v, '.') !== false) ? self::TYPE_FLOAT : $previousType;
        };

        if (!$this->validator->isValid($a)) {
            throw $this->createNewInvalidNumberException($a);
        }

        $type = $getType($a);

        if ($b !== null) {
            if (!$this->validator->isValid($b)) {
                throw $this->createNewInvalidNumberException($b);
            }

            $type = $getType($b, $type);
        }

        return $type;
    }

    /**
     * Much like a "chain-of-responsibility" this method iterates through the available delegates, attempting to perform
     * the desired operation if it exists.
     * If the operation fails due to a library error, it will try the next library. If all libraries fail then
     * it will use the last exception thrown.
     *
     * @param string      $operation
     * @param string      $leftOperand
     * @param string|null $rightOperand
     * @param int|null    $precision
     *
     * @return mixed
     */
    protected function getDelegateResult(
        string $operation,
        string $leftOperand,
        string $rightOperand = null,
        int $precision = null
    ) {
        $type = $this->getOperationType($leftOperand, $rightOperand);
        $exception = null;

        foreach ($this->getDelegates($type) as $library) {
            // In case of future interface changes between delegates, let's see if the method is callable.
            // If not, we'll skip to the next library.
            if (!is_callable([$library, $operation])) {
                continue;
            }

            try {
                return $this->getLibraryResult($library, $operation, $leftOperand, $rightOperand, $precision);
            } catch (\Throwable $e) {
                // Save last exception and try next library.
                $exception = new \RuntimeException($e->getMessage(), $e->getCode(), $e);
                continue;
            }
        }

        //We'll use the last exception thrown, otherwise create one.
        throw $exception ?? $this->createNewUnknownErrorException();
    }

    /**
     * Supported rounding strategies.
     *
     * @return array<int>
     */
    protected static function getSupportedRoundingStrategies(): array
    {
        return [
            PHP_ROUND_HALF_UP,
            PHP_ROUND_HALF_DOWN,
            PHP_ROUND_HALF_EVEN,
            PHP_ROUND_HALF_ODD,
        ];
    }

    /**
     * @param $num
     *
     * @return InvalidNumberException
     */
    protected function createNewInvalidNumberException($num)
    {
        return new InvalidNumberException(sprintf('Invalid number: %s', ($num ?: gettype($num))));
    }

    /**
     * @return \RuntimeException
     */
    protected function createNewUnknownErrorException()
    {
        return new \RuntimeException('Unknown error.');
    }

    /**
     * This method tries to call the operation with the proper number of arguments based on whether they are null.
     *
     * @param MathLibraryInterface $library
     * @param string               $operation
     * @param string               $leftOperand
     * @param string|null          $rightOperand
     * @param int|null             $precision
     *
     * @return mixed
     */
    private function getLibraryResult(
        MathLibraryInterface $library,
        string $operation,
        string $leftOperand,
        string $rightOperand = null,
        int $precision = null
    ) {
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
    }
}
