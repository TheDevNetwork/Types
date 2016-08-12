<?php

namespace Tdn\PhpTypes\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection as CollectionInterface;
use Tdn\PhpTypes\Exception\InvalidTransformationException;
use Tdn\PhpTypes\Exception\InvalidTypeCastException;
use Tdn\PhpTypes\Type\Traits\Boxable;
use Tdn\PhpTypes\Type\Traits\Transmutable;

/**
 * Class Collection.
 *
 * A Collection is a TypeInterface implementation that wraps around a regular PHP array.
 * This object extends Doctrine's ArrayCollection.
 *
 * This object can be extended to create type specific collections. (either primitive or compound)
 *
 * {@inheritDoc}
 */
class Collection extends ArrayCollection implements TransmutableTypeInterface
{
    use Transmutable;
    use Boxable;

    /**
     * {@inheritDoc}
     *
     * @return string|array|int
     */
    public function __invoke(int $toType = Type::ARRAY)
    {
        switch ($toType) {
            case Type::INT:
                return $this->count();
            case Type::ARRAY:
                return $this->toArray();
            case Type::STRING:
                try {
                    return (StringType::valueOf($this->toArray()))(Type::STRING);
                } catch (\Throwable $e) {
                    //throwing exception below
                }

                break;
            default:
        }

        $e = ($e ?? null);
        throw new InvalidTypeCastException(static::class, $this->getTranslatedType($toType), null, 0, $e);
    }

    /**
     * {@inheritDoc}
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
        if ($mixed instanceof CollectionInterface) {
            return $mixed->toArray();
        }

        if ($mixed instanceof TypeInterface) {
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
