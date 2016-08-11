<?php

declare (strict_types = 1);

namespace Tdn\PhpTypes\Math;

use Tdn\PhpTypes\Exception\InvalidNumberException;
use Tdn\PhpTypes\Type\StringType;

/**
 * Class AbstractMathAdapter.
 */
abstract class AbstractMathAdapter implements MathAdapterInterface
{
    /**
     * Represents bcmath extension.
     */
    const EXT_BCMATH = 'bcmath';

    /**
     * Represents gmp extension.
     */
    const EXT_GMP = 'gmp';

    /**
     * Represents SPL (no extension).
     */
    const EXT_SPL = 'spl';

    /**
     * Float operation.
     */
    const TYPE_FLOAT = 'float';

    /**
     * Int operation.
     */
    const TYPE_INT = 'int';

    /**
     * @var NumberValidatorInterface
     */
    private $validator;

    /**
     * @var int
     */
    private $roundingStrategy;

    /**
     * @var string
     */
    private $forcedExtension;

    /**
     * @param NumberValidatorInterface|null $validator
     * @param int                           $roundingStrategy
     * @param string                        $forceExtension
     */
    public function __construct(
        NumberValidatorInterface $validator = null,
        int $roundingStrategy = PHP_ROUND_HALF_UP,
        string $forceExtension = ''
    ) {
        if ($roundingStrategy !== null && !in_array($roundingStrategy, static::getSupportedRoundingStrategies())) {
            throw new \OutOfBoundsException('Unsupported rounding strategy. Please refer to PHP\'s documentation.');
        }

        if ($forceExtension && !in_array(strtolower($forceExtension), static::getSupportedExtensions())) {
            throw new \OutOfBoundsException(
                sprintf(
                    'Unsupported extension %s. Only the following extensions are supported: %s',
                    $forceExtension,
                    implode(', ', static::getSupportedExtensions())
                )
            );
        }

        $this->validator = $validator ?? new DefaultNumberValidator();
        $this->roundingStrategy = $roundingStrategy;
        $this->forcedExtension = $forceExtension;
    }

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
     * Returns BCMath Precision.
     *
     * It differs from standard PHP precision in that it includes the numbers before the decimal period.
     *
     * @param string $leftOperand
     * @param int    $precision
     *
     * @return int
     */
    private function getBcPrecision(string $leftOperand, int $precision) : int
    {
        $operand = StringType::create($leftOperand);
        if ($operand->contains('.')) {
            return $operand->countSubstr('.') + $precision;
        }

        return $precision;
    }

    /**
     * Return supported PHP extensions. (SPL for no extension).
     *
     * @return array<string,string>
     */
    protected static function getSupportedExtensions() : array
    {
        return [
            self::EXT_BCMATH => self::EXT_BCMATH,
            self::EXT_GMP => self::EXT_GMP,
            self::EXT_SPL => self::EXT_SPL,
        ];
    }

    /**
     * Supported rounding strategies.
     *
     * @return array<int>
     */
    protected static function getSupportedRoundingStrategies() : array
    {
        return [
            PHP_ROUND_HALF_UP,
            PHP_ROUND_HALF_DOWN,
            PHP_ROUND_HALF_EVEN,
            PHP_ROUND_HALF_ODD,
        ];
    }

    /**
     * Returns true if precision library is installed.
     *
     * @return bool
     */
    protected function hasPrecisionLibrary() : bool
    {
        return extension_loaded(self::EXT_BCMATH);
    }

    /**
     * @return bool
     */
    protected function hasBetterIntegerHandling() : bool
    {
        return extension_loaded(self::EXT_GMP);
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
     * Returns the forced extension. Empty if no extension is being forced.
     *
     * @return string
     */
    protected function getForcedExtension() : string
    {
        return $this->forcedExtension;
    }

    /**
     * Returns current rounding strategy.
     *
     * @return int
     */
    protected function getRoundingStrategy() : int
    {
        return $this->roundingStrategy;
    }

    /**
     * Returns true if the operation is deemed adequate for a precision library based on the rules.
     *
     * @return bool
     */
    protected function isPrecisionLibraryOperation() : bool
    {
        return $this->hasPrecisionLibrary() &&
        (static::EXT_BCMATH === $this->getForcedExtension() ||
            (static::EXT_SPL !== $this->getForcedExtension() && static::EXT_GMP !== $this->getForcedExtension()));
    }

    /**
     * Returns true if the operation is deemed adequate for an integer library based on the rules.
     *
     * @param string $type
     *
     * @return bool
     */
    protected function isIntegerLibraryOperation(string $type) : bool
    {
        return $this->hasBetterIntegerHandling() &&
            (static::EXT_GMP === $this->getForcedExtension() ||
                (static::EXT_SPL !== $this->getForcedExtension() && static::EXT_BCMATH !== $this->getForcedExtension()
                    && $type !== self::TYPE_FLOAT));
    }

    /**
     * Returns the result of the given precision operation (callable).
     *
     * Uses BCMath.
     *
     * @param callable    $operator
     * @param string      $leftOperand
     * @param string|null $rightOperand
     * @param int|null    $precision
     *
     * @return string
     */
    protected function getPrecisionResult(
        callable $operator,
        string $leftOperand,
        string $rightOperand = null,
        int $precision = null
    ) : string {
        if ($precision !== null) {
            $precision = $this->getBcPrecision($leftOperand, $precision);
            if ($rightOperand !== null) {
                return strval($operator($leftOperand, $rightOperand, $precision));
            }

            return strval($operator($leftOperand, $precision));
        }

        return strval($operator($leftOperand, $rightOperand));
    }

    /**
     * Returns the result of the given integer operation (callable).
     *
     * Uses GMP math.
     *
     * @param callable   $operator
     * @param string     $leftOperand
     * @param mixed|null $rightOperand
     *
     * @return string
     */
    protected function getNonPrecisionResult(
        callable $operator,
        string $leftOperand,
        $rightOperand = null
    ) : string {
        if ($rightOperand !== null) {
            return gmp_strval($operator($leftOperand, $rightOperand));
        }

        return gmp_strval($operator($leftOperand));
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
