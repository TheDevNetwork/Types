<?php

declare (strict_types = 1);

namespace Tdn\PhpTypes\Type\Traits;

use Tdn\PhpTypes\Exception\InvalidTypeCastException;
use Tdn\PhpTypes\Type\Collection;
use Tdn\PhpTypes\Type\StringType;
use Tdn\PhpTypes\Type\BooleanType;
use Tdn\PhpTypes\Type\IntType;
use Tdn\PhpTypes\Type\FloatType;
use Tdn\PhpTypes\Type\Type;
use Tdn\PhpTypes\Type\TransmutableTypeInterface;

/**
 * Trait Transmutable.
 */
trait Transmutable
{
    /**
     * @return StringType
     */
    public function toStringType() : StringType
    {
        return StringType::valueOf($this);
    }

    /**
     * @return BooleanType
     */
    public function toBoolType() : BooleanType
    {
        return BooleanType::valueOf($this);
    }

    /**
     * @return IntType
     */
    public function toIntType() : IntType
    {
        return IntType::valueOf($this);
    }

    /**
     * @return FloatType
     */
    public function toFloatType() : FloatType
    {
        return FloatType::valueOf($this);
    }

    /**
     * @return Collection
     */
    public function toCollection() : Collection
    {
        return Collection::valueOf($this);
    }

    /**
     * @return bool
     */
    public function toBool() : bool
    {
        if ($this instanceof TransmutableTypeInterface) {
            return $this(Type::BOOL);
        }

        throw $this->createInvalidTransformationException('bool');
    }

    /**
     * @return int
     */
    public function toInt() : int
    {
        if ($this instanceof TransmutableTypeInterface) {
            return $this(Type::INT);
        }

        throw $this->createInvalidTransformationException('int');
    }

    /**
     * @return float
     */
    public function toFloat() : float
    {
        if ($this instanceof TransmutableTypeInterface) {
            return $this(Type::FLOAT);
        }

        throw $this->createInvalidTransformationException('float');
    }

    /**
     * @return string
     */
    public function toString() : string
    {
        if ($this instanceof TransmutableTypeInterface) {
            return $this(Type::STRING);
        }

        throw $this->createInvalidTransformationException('string');
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        if ($this instanceof Collection) {
            return parent::toArray();
        }

        if ($this instanceof TransmutableTypeInterface) {
            return $this(Type::ARRAY);
        }

        throw $this->createInvalidTransformationException('array');
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->toString();
    }

    private function createInvalidTransformationException($type)
    {
        return new InvalidTypeCastException(static::class, $type);
    }
}
