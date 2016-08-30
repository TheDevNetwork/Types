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
    public function toStringType() : StringType;

    /**
     * Converts current type to BooleanType.
     *
     * @return BooleanType
     */
    public function toBoolType() : BooleanType;

    /**
     * Converts current type to IntType.
     *
     * @return IntType
     */
    public function toIntType() : IntType;

    /**
     * Converts current type to FloatType.
     *
     * @return FloatType
     */
    public function toFloatType() : FloatType;

    /**
     * @return bool
     */
    public function toBool() : bool;

    /**
     * @return int
     */
    public function toInt() : int;

    /**
     * @return float
     */
    public function toFloat() : float;

    /**
     * @return string
     */
    public function toString() : string;

    /**
     * @return array
     */
    public function toArray(): array;
}
