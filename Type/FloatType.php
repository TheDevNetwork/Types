<?php

declare (strict_types = 1);

namespace Tdn\PhpTypes\Type;

use Tdn\PhpTypes\Exception\InvalidTransformationException;
use Tdn\PhpTypes\Exception\InvalidTypeCastException;
use Tdn\PhpTypes\Math\MathAdapterInterface;

/**
 * Class FloatType.
 */
class FloatType extends AbstractNumberType
{
    /**
     * @param float                     $float
     * @param null|int                  $precision
     * @param MathAdapterInterface|null $mathAdapter
     */
    public function __construct(float $float, int $precision = null, MathAdapterInterface $mathAdapter = null)
    {
        parent::__construct($float, $precision, $mathAdapter);
    }

    /**
     * Returns the primitive value of current instance casted to specified type.
     *
     * @param int $toType Default: Type::FLOAT. Options: Type::FLOAT, Type::STRING, Type::INT
     *
     * @throws InvalidTransformationException when casted to an unsupported type.
     *
     * @return string|int|float
     */
    public function __invoke(int $toType = Type::FLOAT)
    {
        if ($toType === Type::STRING) {
            return (string) $this->get();
        }

        if ($toType === Type::INT) {
            return intval($this->get());
        }

        if ($toType !== Type::FLOAT) {
            throw new InvalidTypeCastException(static::class, $this->getTranslatedType($toType));
        }

        return $this->value;
    }

    /**
     * Returns a FloatType from a mixed type/scalar.
     *
     * @param $mixed
     * @param int|null $precision
     *
     * @return FloatType
     */
    public static function valueOf($mixed, int $precision = null) : FloatType
    {
        return new static(self::asFloat($mixed), $precision);
    }

    /**
     * Returns a mixed variable as a float.
     *
     * @param mixed $mixed
     *
     * @return float
     */
    private static function asFloat($mixed) : float
    {
        return static::asSubType('floatval', $mixed);
    }
}
