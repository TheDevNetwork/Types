<?php

declare (strict_types = 1);

namespace Tdn\PhpTypes\Type;

use Tdn\PhpTypes\Exception\InvalidTransformationException;
use Tdn\PhpTypes\Math\MathAdapterInterface;

/**
 * Class IntType.
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
     * Returns the primitive value of current instance casted to specified type.
     *
     * @param int $toType Default: Type::INT. Options: Type::FLOAT, Type::STRING, Type::INT
     *
     * @throws InvalidTransformationException when casted to an unsupported type.
     *
     * @return string|float|int
     */
    public function __invoke(int $toType = Type::INT)
    {
        if ($toType === Type::STRING) {
            return (string) $this->get();
        }

        if ($toType === Type::FLOAT) {
            return (float) $this->get();
        }

        if ($toType !== Type::INT) {
            throw new InvalidTransformationException(static::class, $this->getTranslatedType($toType));
        }

        return $this->get();
    }

    /**
     * Returns a IntType from a mixed type/scalar.
     *
     * @param mixed    $mixed
     * @param int|null $precision Always 0 in int...
     *
     * @return IntType
     */
    public static function valueOf($mixed, int $precision = null) : IntType
    {
        return new static(self::asInt($mixed));
    }

    /**
     * Returns a mixed variable as a int.
     *
     * @param mixed $mixed
     *
     * @return int
     */
    private static function asInt($mixed) : int
    {
        return static::asSubType(
            function ($v) {
                return intval(round($v));
            },
            $mixed
        );
    }
}
