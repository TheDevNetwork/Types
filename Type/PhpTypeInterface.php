<?php

declare (strict_types = 1);

namespace Tdn\PhpTypes\Type;

/**
 * Interface PhpTypeInterface.
 */
interface PhpTypeInterface extends BoxedTypeInterface
{
    /**
     * Returns a TypeInterface from a mixed scalar/type.
     *
     * @param $mixed
     *
     * @return PhpTypeInterface
     */
    public static function valueOf($mixed);
}
