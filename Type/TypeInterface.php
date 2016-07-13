<?php

declare (strict_types = 1);

namespace Tdn\PhpTypes\Type;

/**
 * Interface TypeInterface.
 */
interface TypeInterface extends BoxedTypeInterface
{
    /**
     * Returns a TypeInterface from a mixed scalar/type.
     *
     * @param $mixed
     *
     * @return TypeInterface
     */
    public static function valueOf($mixed);
}
