<?php

namespace Tdn\PhpTypes\Type;

/**
 * Interface TypeInterface.
 */
interface TypeInterface
{
    /**
     * A little bit of java goodness. Should be useful in the upcoming php7.
     *
     * Returns an instance of the implementing class with it's value evaluated from the argument.
     * E.g. BooleanType->valueOf("false") returns new BooleanType(false).
     *
     * @param mixed $mixed
     *
     * @return static
     */
    public static function valueOf($mixed);
}
