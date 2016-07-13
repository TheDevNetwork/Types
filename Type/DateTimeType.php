<?php

declare (strict_types = 1);

namespace Tdn\PhpTypes\Type;

use Tdn\PhpTypes\Exception\InvalidTransformationException;
use Tdn\PhpTypes\Type\Traits\Boxable;
use Carbon\Carbon;

/**
 * Class DateTimeType.
 *
 * Extends carbon. (https://github.com/briannesbitt/Carbon)
 */
class DateTimeType extends Carbon implements TypeInterface
{
    use Boxable;

    /**
     * @param string|null               $time
     * @param \DateTimezone|string|null $tz
     */
    public function __construct(string $time = null, $tz = null)
    {
        parent::__construct($time, $tz);
    }

    /**
     * Returns a DateTimeType from a mixed type/scalar.
     *
     * @param $mixed
     *
     * @return DateTimeType
     */
    public static function valueOf($mixed) : DateTimeType
    {
        if ($mixed instanceof StringType || 'string' === $type = strtolower(gettype($mixed))) {
            return new static((string) $mixed);
        }

        throw new InvalidTransformationException($type, static::class);
    }

    /**
     * Returns the primitive value of current instance casted to specified type.
     *
     * @param int|null $toType Default: null. Options: null|Type::STRING
     *
     * @throws InvalidTransformationException when casted to an unsupported type.
     *
     * @return string|DateTimeType
     */
    public function __invoke(int $toType = null)
    {
        if ($toType === Type::STRING) {
            return $this->format('Y-m-d H:i:s');
        }

        if ($toType !== null) {
            throw new InvalidTransformationException(static::class, $this->getTranslatedType($toType));
        }

        return $this;
    }
}
