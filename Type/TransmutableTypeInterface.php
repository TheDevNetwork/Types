<?php

declare (strict_types = 1);

namespace Tdn\PhpTypes\Type;

/**
 * Interface TransmutableTypeInterface.
 */
interface TransmutableTypeInterface extends TypeInterface
{
    /**
     * Converts current type to StringType.
     *
     * @return StringType
     */
    public function toString() : StringType;

    /**
     * Converts current type to BooleanType.
     *
     * @return BooleanType
     */
    public function toBool() : BooleanType;

    /**
     * Converts current type to IntType.
     *
     * @return IntType
     */
    public function toInt() : IntType;

    /**
     * Converts current type to FloatType.
     *
     * @return FloatType
     */
    public function toFloat() : FloatType;
}
