<?php

declare (strict_types = 1);

namespace Tdn\PhpTypes\Type\Traits;

use Tdn\PhpTypes\Type\Collection;
use Tdn\PhpTypes\Type\StringType;
use Tdn\PhpTypes\Type\BooleanType;
use Tdn\PhpTypes\Type\IntType;
use Tdn\PhpTypes\Type\FloatType;

/**
 * Trait Transmutable.
 */
trait Transmutable
{
    /**
     * @return StringType
     */
    public function toString() : StringType
    {
        return StringType::valueOf($this);
    }

    /**
     * @return BooleanType
     */
    public function toBool() : BooleanType
    {
        return BooleanType::valueOf($this);
    }

    /**
     * @return IntType
     */
    public function toInt() : IntType
    {
        return IntType::valueOf($this);
    }

    /**
     * @return FloatType
     */
    public function toFloat() : FloatType
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
}
