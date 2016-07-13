<?php

declare(strict_types=1);

namespace Tdn\PhpTypes\Type;

use Tdn\PhpTypes\Exception\InvalidTypeCastException;
use Tdn\PhpTypes\Type\Traits\ValueType;
use Tdn\PhpTypes\Type\Traits\Boxable;
use Tdn\PhpTypes\Type\Traits\Transmutable;
use Tdn\PhpTypes\Exception\InvalidTransformationException;

/**
 * Class BooleanType.
 *
 * A BooleanType is a TypeInterface implementation that wraps around a regular PHP bool.
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
     * {@inheritdoc}
     *
     * @return string|bool
     */
    public function __invoke(int $toType = Type::BOOL)
    {
        switch ($toType) {
            case Type::STRING:
                return ($this->value) ? 'true' : 'false';
            case Type::BOOL:
                return $this->value;
            default:
                throw new InvalidTypeCastException(static::class, $this->getTranslatedType($toType));
        }
    }

    /**
     * Returns true if boolean is true.
     *
     * @return bool
     */
    public function isTrue(): bool
    {
        return $this->value;
    }

    /**
     * Returns true if boolean is false.
     *
     * @return bool
     */
    public function isFalse(): bool
    {
        return !$this->value;
    }

    /**
     * {@inheritdoc}
     *
     * @return BooleanType
     */
    public static function valueOf($mixed): BooleanType
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
    private static function asBool($mixed): bool
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
    protected static function getFromStringMap($key): bool
    {
        $map = [
            'true' => true,
            'on' => true,
            'yes' => true,
        ];

        if (array_key_exists($key, $map)) {
            return $map[$key];
        }

        // All other strings will always be false.
        return false;
    }
}
