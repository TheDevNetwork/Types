<?php

declare(strict_types=1);

namespace Tdn\PhpTypes\Type;

use Tdn\PhpTypes\Exception\InvalidTypeCastException;
use Tdn\PhpTypes\Math\MathAdapterInterface;

/**
 * Class FloatType.
 *
 * A FloatType is a TypeInterface implementation that wraps around a regular PHP float / double.
 *
 * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @return string|int|float
     */
    public function __invoke(int $toType = Type::FLOAT)
    {
        switch ($toType) {
            case Type::FLOAT:
                return $this->value;
            case Type::INT:
                return (int) $this->get();
            case Type::STRING:
                return (string) $this->get();
            default:
                throw new InvalidTypeCastException(static::class, $this->getTranslatedType($toType));
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return FloatType
     */
    public static function valueOf($mixed, int $precision = null): FloatType
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
    private static function asFloat($mixed): float
    {
        return static::asSubType('floatval', $mixed);
    }
}
