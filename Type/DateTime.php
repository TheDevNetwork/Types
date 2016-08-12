<?php

declare (strict_types = 1);

namespace Tdn\PhpTypes\Type;

use Tdn\PhpTypes\Exception\InvalidTransformationException;
use Tdn\PhpTypes\Exception\InvalidTypeCastException;
use Tdn\PhpTypes\Type\Traits\Boxable;
use Carbon\Carbon;

/**
 * Class DateTime.
 *
 * Extends carbon. (https://github.com/briannesbitt/Carbon)
 */
class DateTime extends Carbon implements TypeInterface
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
     * {@inheritdoc}
     *
     * @return DateTime
     */
    public static function valueOf($mixed) : DateTime
    {
        if ($mixed instanceof StringType || 'string' === $type = strtolower(gettype($mixed))) {
            return new static((string) $mixed);
        }

        throw new InvalidTransformationException($type, static::class);
    }

    /**
     * {@inheritdoc}
     *
     * @return string|DateTime
     */
    public function __invoke(int $toType = null)
    {
        switch ($toType) {
            case null:
                return $this;
            case Type::STRING:
                return $this->format('Y-m-d H:i:s');
            default:
                throw new InvalidTypeCastException(static::class, $this->getTranslatedType($toType));
        }
    }
}
