<?php

namespace Tdn\PhpTypes\Type;

use ArrayIterator;
use Closure;
use Doctrine\Common\Collections\Expr\ClosureExpressionVisitor;
use Doctrine\Common\Collections\Collection as CollectionInterface;
use Tdn\PhpTypes\Exception\InvalidTransformationException;
use Tdn\PhpTypes\Exception\InvalidTypeCastException;
use Tdn\PhpTypes\Type\Traits\Boxable;
use Tdn\PhpTypes\Type\Traits\Transmutable;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Selectable;

/**
 * Class Collection.
 *
 * A Collection is a TypeInterface implementation that wraps around a regular PHP array.
 * This object implements Doctrine's Collection and is analogous to Doctrine's
 * ArrayCollection, with extra functionality.
 *
 * This object can be extended to create type specific collections. (either primitive or compound)
 */
class Collection implements TransmutableTypeInterface, CollectionInterface, Selectable
{
    use Transmutable;
    use Boxable;

    /**
     * @var null|string
     */
    private $type;

    /**
     * @var array
     */
    private $elements;

    /**
     * @param array       $elements
     * @param null|string $type
     */
    public function __construct(array $elements = array(), string $type = null)
    {
        $this->type = $type;
        $this->elements = array_map(function ($element) {
            return $this->getRealValue($element);
        }, $elements);
    }

    /**
     * @param $element
     *
     * @return bool
     */
    public function unshift($element)
    {
        array_unshift($this->elements, $this->getRealValue($element));

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function first()
    {
        return reset($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function last()
    {
        return end($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return key($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        return next($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return current($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        if (!isset($this->elements[$key]) && !array_key_exists($key, $this->elements)) {
            return null;
        }

        $removed = $this->elements[$key];
        unset($this->elements[$key]);

        return $removed;
    }

    /**
     * {@inheritdoc}
     */
    public function removeElement($element)
    {
        $key = array_search($element, $this->elements, true);

        if ($key === false) {
            return false;
        }

        unset($this->elements[$key]);

        return true;
    }

    /**
     * Required by interface ArrayAccess.
     *
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return $this->containsKey($offset);
    }

    /**
     * Required by interface ArrayAccess.
     *
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Required by interface ArrayAccess.
     *
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        if (!isset($offset)) {
            return $this->add($value);
        }

        $this->set($offset, $value);
    }

    /**
     * Required by interface ArrayAccess.
     *
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        return $this->remove($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function containsKey($key)
    {
        return isset($this->elements[$key]) || array_key_exists($key, $this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function contains($element)
    {
        return in_array($element, $this->elements, true);
    }

    /**
     * {@inheritdoc}
     */
    public function exists(Closure $p)
    {
        foreach ($this->elements as $key => $element) {
            if ($p($key, $element)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function indexOf($element)
    {
        return array_search($element, $this->elements, true);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        return isset($this->elements[$key]) ? $this->elements[$key] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getKeys()
    {
        return array_keys($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function getValues()
    {
        return array_values($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        return empty($this->elements);
    }

    /**
     * Required by interface IteratorAggregate.
     *
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function map(Closure $func)
    {
        return new static(array_map($func, $this->elements));
    }

    /**
     * {@inheritdoc}
     */
    public function filter(Closure $p)
    {
        return new static(array_filter($this->elements, $p));
    }

    /**
     * {@inheritdoc}
     */
    public function forAll(Closure $p)
    {
        foreach ($this->elements as $key => $element) {
            if (!$p($key, $element)) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function partition(Closure $p)
    {
        $matches = $noMatches = array();

        foreach ($this->elements as $key => $element) {
            if ($p($key, $element)) {
                $matches[$key] = $element;
            } else {
                $noMatches[$key] = $element;
            }
        }

        return array(new static($matches), new static($noMatches));
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->elements = array();
    }

    /**
     * {@inheritdoc}
     */
    public function slice($offset, $length = null)
    {
        return array_slice($this->elements, $offset, $length, true);
    }

    /**
     * {@inheritdoc}
     */
    public function matching(Criteria $criteria)
    {
        $expr = $criteria->getWhereExpression();
        $filtered = $this->elements;

        if ($expr) {
            $visitor = new ClosureExpressionVisitor();
            $filter = $visitor->dispatch($expr);
            $filtered = array_filter($filtered, $filter);
        }

        if ($orderings = $criteria->getOrderings()) {
            $next = null;
            foreach (array_reverse($orderings) as $field => $ordering) {
                $next = ClosureExpressionVisitor::sortByField($field, $ordering == Criteria::DESC ? -1 : 1);
            }

            if (null !== $next) {
                uasort($filtered, $next);
            }
        }

        $offset = $criteria->getFirstResult();
        $length = $criteria->getMaxResults();

        if (null !== $offset || null !== $length) {
            $filtered = array_slice($filtered, (int) $offset, $length);
        }

        return new static($filtered);
    }

    /**
     * @throws \LogicException when not collection is untyped or collection type does not contain __toString method
     *
     * @return static
     */
    public function unique()
    {
        $closure = function ($key, $value) {
            return is_string($value);
        };

        if ($this->forAll($closure) || (null !== $this->type && method_exists($this->type, '__toString'))) {
            $result = new static(array_unique($this->elements, SORT_STRING), $this->type);

            return $result;
        }

        throw new \LogicException('Collection instance is not typed, or type has no string support.');
    }

    /**
     * @param mixed $value
     */
    public function add($value)
    {
        $this->elements[] = $this->getRealValue($value);

        return true;
    }

    public function set($key, $value)
    {
        $this->elements[$key] = $this->getRealValue($value);
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
                return $this->elements;
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
    public function merge(CollectionInterface $collection, $keepDupes = false): Collection
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
    public static function valueOf($mixed): Collection
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
    private static function asArray($mixed): array
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
     *
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
