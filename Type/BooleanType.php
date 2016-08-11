<?php

declare (strict_types = 1);

namespace Tdn\PhpTypes\Type;

use Tdn\PhpTypes\Type\Traits\ValueType;
use Tdn\PhpTypes\Type\Traits\Boxable;
use Tdn\PhpTypes\Type\Traits\Transmutable;
use Tdn\PhpTypes\Exception\InvalidTransformationException;

/**
 * Class BooleanType.
 *
 * Boolean wrapper class. Provides additional features over using a scalar, such as boxing.
 */
class BooleanType implements TransmutableTypeInterface, ValueTypeInterface
{
    use ValueType;
    use Transmutable;
    use Boxable;

    /**
     * @param bool $bool
     */
    public function __construct(bool $bool)
    {
        $this->value = $bool;
    }

    /**
     * Returns the primitive value of current instance casted to specified type.
     *
     * @param int $toType Default: Type::BOOL. Options: Type::BOOL, Type::STRING
     *
     * @throws InvalidTransformationException when casted to an unsupported type.
     *
     * @return string|bool
     */
    public function __invoke(int $toType = Type::BOOL)
    {
        if ($toType === Type::STRING) {
            return ($this->value) ? 'true' : 'false';
        }

        if ($toType !== Type::BOOL) {
            throw new InvalidTransformationException(static::class, $this->getTranslatedType($toType));
        }

        return $this->value;
    }

    /**
     * Returns true if boolean is true.
     *
     * @return bool
     */
    public function isTrue() : bool
    {
        return $this();
    }

    /**
     * Returns true if boolean is false.
     *
     * @return bool
     */
    public function isFalse() : bool
    {
        return !$this();
    }

    /**
     * Returns a BooleanType from a mixed type/scalar.
     *
     * @param mixed $mixed
     *
     * @return BooleanType
     */
    public static function valueOf($mixed) : BooleanType
    {
        return new static(self::asBool($mixed));
    }

    /**
     * Returns a mixed variable as a bool.
     *
     * @param mixed $mixed
     *
     * @return bool
     */
    private static function asBool($mixed) : bool
    {
        if ($mixed instanceof static || $mixed instanceof StringType) {
            $mixed = $mixed->get();
        }

        $type = strtolower(gettype($mixed));
        switch ($type) {
            case 'boolean':
                return (bool) $mixed;
            case 'string':
                return self::getFromStringMap($mixed);
            case 'resource':
                // Don't really care for this, and might go away soon unless someone actually ends up using it.
                return ($mixed === null || $mixed === false) ? false : true;
            default:
                throw new InvalidTransformationException($type, static::class);
        }
    }

    /**
     * Maps specific strings to a boolean value.
     *
     * @param $key
     *
     * @return bool
     */
    protected static function getFromStringMap($key) : bool
    {
        $map = array(
            'true' => true,
            'on' => true,
            'yes' => true,
            'false' => false,
            'off' => false,
            'no' => false,
        );

        if (array_key_exists($key, $map)) {
            return $map[$key];
        }

        return false;
    }
}
