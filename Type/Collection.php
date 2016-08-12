<?php

namespace Tdn\PhpTypes\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Tdn\PhpTypes\Exception\InvalidTransformationException;
use Tdn\PhpTypes\Exception\InvalidTypeCastException;
use Tdn\PhpTypes\Type\Traits\Boxable;
use Tdn\PhpTypes\Type\Traits\Transmutable;

/**
 * Class Collection.
 *
 * Caveats when boxing:
 * Since boxing only boxes that it is a Collection and not the actual collection object, if a specific type
 * collection is needed, extend this object and override appropriate methods to ensure strict type.
 *
 * This can be done by overriding the constructor, set, and add methods.
 */
class Collection extends ArrayCollection implements TransmutableTypeInterface
{
    use Transmutable;
    use Boxable;

    /**
     * Returns the primitive value of current instance casted to specified type.
     *
     * @param int $toType Default: Type::ARRAY. Options: Type::INT, Type::STRING, Type::ARRAY
     *
     * @throws InvalidTransformationException when casted to an unsupported type.
     *
     * @return string|array|int
     */
    public function __invoke(int $toType = Type::ARRAY)
    {
        if ($toType === Type::INT) {
            return $this->count();
        }

        if ($toType === Type::ARRAY) {
            return $this->toArray();
        }

        if ($toType === Type::STRING) {
            try {
                return (StringType::valueOf($this->toArray()))(Type::STRING);
            } catch (\Throwable $e) {
                //continue to exception below.
            }
        }

        throw new InvalidTypeCastException(static::class, $this->getTranslatedType($toType));
    }

    /**
     * Returns a Collection from a mixed type/scalar.
     *
     * @param $mixed
     *
     * @return Collection
     */
    public static function valueOf($mixed) : Collection
    {
        return new static(self::asArray($mixed));
    }

    /**
     * Returns a mixed variable as an array.
     *
     * @param $mixed
     *
     * @return array
     */
    private static function asArray($mixed) : array
    {
        if ($mixed instanceof Collection) {
            return $mixed->toArray();
        }

        if ($mixed instanceof BoxedTypeInterface) {
            return [$mixed()];
        }

        $type = strtolower(gettype($mixed));
        switch ($type) {
            case 'integer':
            case 'double':
            case 'float':
            case 'string':
            case 'object':
            case 'resource':
            case 'boolean':
                return [$mixed];
            case 'array':
                return $mixed;
            default:
                throw new InvalidTransformationException($type, static::class);
        }
    }
}
