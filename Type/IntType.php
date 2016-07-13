<?php

declare(strict_types=1);

namespace Tdn\PhpTypes\Type;

use Tdn\PhpTypes\Exception\InvalidTypeCastException;
use Tdn\PhpTypes\Math\MathAdapterInterface;

/**
 * Class IntType.
 *
 * A IntType is a TypeInterface implementation that wraps around a regular PHP int.
 *
 * {@inheritdoc}
 */
class IntType extends AbstractNumberType
{
    /**
     * @param int                       $int
     * @param MathAdapterInterface|null $mathAdapter
     */
    public function __construct(int $int, MathAdapterInterface $mathAdapter = null)
    {
        parent::__construct($int, 0, $mathAdapter);
    }

    /**
     * {@inheritdoc}
     *
     * @return string|float|int
     */
    public function __invoke(int $toType = Type::INT)
    {
        switch ($toType) {
            case Type::INT:
                return $this->value;
            case Type::FLOAT:
                return (float) $this->get();
            case Type::STRING:
                return (string) $this->get();
            default:
                throw new InvalidTypeCastException(static::class, $this->getTranslatedType($toType));
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return IntType
     */
    public static function valueOf($mixed, int $precision = null): IntType
    {
        //Dealing with big integers. Best to use FloatType.
        if (is_numeric($mixed) && ($mixed >= PHP_INT_MAX && !ctype_digit($mixed))) {
            throw new \RuntimeException('Incorrect type used. Use FloatType instead.');
        }

        return new static(self::asInt($mixed));
    }

    /**
     * Returns a mixed variable as a int.
     *
     * @param mixed $mixed
     *
     * @return int
     */
    private static function asInt($mixed): int
    {
        return static::asSubType(
            function ($v) {
                return intval(round($v));
            },
            $mixed
        );
    }
}
