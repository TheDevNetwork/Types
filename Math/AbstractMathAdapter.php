<?php

declare (strict_types = 1);

namespace Tdn\PhpTypes\Math;

use Tdn\PhpTypes\Exception\InvalidNumberException;
use Tdn\PhpTypes\Math\Library\BcMath;
use Tdn\PhpTypes\Math\Library\Gmp;
use Tdn\PhpTypes\Math\Library\MathLibraryInterface;
use Tdn\PhpTypes\Math\Library\Spl;
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
    private $mathLibraries = [];

    /**
     * @param NumberValidatorInterface|null $validator
     * @param MathLibraryInterface          $mathLibrary
     * @param int                           $roundingStrategy
     */
    public function __construct(
        NumberValidatorInterface $validator = null,
        MathLibraryInterface $mathLibrary = null,
        int $roundingStrategy = PHP_ROUND_HALF_UP
    ) {
        if ($roundingStrategy !== null && !in_array($roundingStrategy, static::getSupportedRoundingStrategies())) {
            throw new \OutOfBoundsException('Unsupported rounding strategy. Please refer to PHP\'s documentation.');
        }

        $this->validator = $validator ?? new DefaultNumberValidator();
        $this->mathLibraries = $mathLibrary ? [$mathLibrary] : $this->getSupportedMathLibraries($roundingStrategy);
    }

    /**
     * @param int $roundingStrategy
     *
     * @return array|MathLibraryInterface[]
     */
    abstract protected function getSupportedMathLibraries(int $roundingStrategy) : array;

    /**
     * Returns the precision of number.
     *
     * @param string $number
     *
     * @return int
     */
    public function getPrecision($number) : int
    {
        if ($this->validator->isValid($number)) {
            $string = StringType::valueOf($number);
            if ($string->contains('.')) {
                return $string->substr(($string->indexOf('.') + 1), $string->length())->count();
            }

            return 0;
        }

        throw new InvalidNumberException(sprintf('Invalid number: %s', ($number ?: gettype($number))));
    }

    /**
     * Iterates through libraries to operate on.
     *
     * @param string $type
     *
     * @return \Generator|MathLibraryInterface[]
     */
    protected function getLibraryForOperation(string $type) : \Generator
    {
        foreach ($this->mathLibraries as $library) {
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
        if ($type !== self::TYPE_INT || $leftOperand < 0 || $rightOperand < 0) {
            throw $this->createNewInvalidNumberException('Arguments must be real numbers.');
        }

        return true;
    }

    /**
     * Ensures operands are valid and returns the operation type.
     *
     * @param string      $a
     * @param string|null $b
     *
     * @throws InvalidNumberException when an operand is not a valid number.
     *
     * @return string
     */
    protected function getOperationType(string $a, string $b = null) : string
    {
        if (!$this->validator->isValid($a)) {
            throw $this->createNewInvalidNumberException($a);
        }

        $getType = function ($v, $firstType = null) {
            $firstType = $firstType ?? self::TYPE_INT;

            return (strpos($v, '.') !== false) ? self::TYPE_FLOAT : $firstType;
        };

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
     * Supported rounding strategies.
     *
     * @return array<int>
     */
    private static function getSupportedRoundingStrategies() : array
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
    private function createNewInvalidNumberException($num)
    {
        return new InvalidNumberException(sprintf('Invalid number: %s', ($num ?: gettype($num))));
    }
}
