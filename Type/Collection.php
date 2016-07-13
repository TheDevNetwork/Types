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
 * {@inheritdoc}
 */
class Collection extends ArrayCollection implements TransmutableTypeInterface
{
    use Transmutable;
    use Boxable;

    /**
     * @var null|string
     */
    private $type;

    /**
     * @param array $elements
     * @param null|string $type
     */
    public function __construct(array $elements = array(), string $type = null)
    {
        $this->type = $type;
        foreach ($elements as &$element) {
            $element = $this->getRealValue($element);
        }

        parent::__construct($elements);
    }

    /**
     * @param mixed $value
     */
    public function add($value)
    {
        parent::add($this->getRealValue($value));
    }

    public function set($key, $value)
    {
        parent::set($key, $this->getRealValue($value));
    }

    /**
     * {@inheritdoc}
     *
     * @return string|array|int
     */
    public function __invoke(int $toType = Type::ARRAY)
    {
        $e = null;
        switch ($toType) {
            case Type::INT:
                return $this->count();
            case Type::ARRAY:
                return $this->toArray();
            case Type::STRING:
                try {
                    return (StringType::valueOf($this))(Type::STRING);
                } catch (\Throwable $e) {
                    $e = new \ErrorException($e->getMessage());
                }
                // Intentionally throwing exception below.
            default:
                throw new InvalidTypeCastException(static::class, $this->getTranslatedType($toType), null, 0, $e);
        }
    }

    /**
     * @param CollectionInterface $collection
     * @param bool                $keepDupes
     *
     * @return Collection
     */
    public function merge(CollectionInterface $collection, $keepDupes = false) : Collection
    {
        if ($keepDupes) {
            return new self(array_merge($this->toArray(), $collection->toArray()));
        }

        return new self($this->toArray() + $collection->toArray());
    }

    /**
     * {@inheritdoc}
     *
     * @return Collection
     */
    public static function valueOf($mixed) : Collection
    {
        return new static(self::asArray($mixed));
    }

    /**
     * @param string $delimeter
     *
     * @return StringType
     */
    public function implode(string $delimeter): StringType
    {
        return StringType::create(implode($delimeter, $this->toArray()));
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

    /**
     * @param $value
     * @return mixed
     */
    private function getRealValue($value)
    {
        if (null !== $this->type && class_exists($this->type) && !$value instanceof $this->type) {
            return new $this->type($value);
        }

        return $value;
    }
}
