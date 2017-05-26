<?php

declare(strict_types=1);

namespace Tdn\PhpTypes\Type;

use Tdn\PhpTypes\Exception\InvalidTypeCastException;

/**
 * Interface TypeInterface.
 *
 * Warning: Using (un-)serialize() on a TypeInterface instance is not a supported use-case
 * and may break when we change the internals in the future. If you need to
 * serialize a TypeInterface use __invoke and reconstruct the TypeInterface
 * manually.
 */
interface TypeInterface
{
    /**
     * Boxes a variable to a specific type, including future reassignment as a primitive.
     * Optionally takes value or instance of the variable.
     * If more than one argument should be passed to constructor, then an instance should be passed explicitly instead
     * of a primitive for $value argument.
     *
     * For examples please view the example.php file.
     *
     * @param null  &$pointer Anmpty variable to box (the pointer)
     * @param mixed $value    the primitive value to pass the constructor OR an instance of the type
     *
     * @throws \LogicException when the pointer has previously been declared
     * @throws \LogicException when the pointer has previously been declared
     * @throws \TypeError      when an invalid argument is passed as value or assigned to pointer
     */
    public static function box(&$pointer, $value = null);

    /**
     * Cast object to primitive type. Casts to logical primitive by default. (E.g. BooleanType -> bool).
     *
     * @param int|null $toType Type to cast to. Default: varies.
     *
     * @throws InvalidTypeCastException when casted to an unsupported type
     *
     * @return bool|float|int|string|array|DateTimeType
     */
    public function __invoke(?int $toType = null);

    /**
     * Returns an instance of TypeInterface from a mixed scalar/type.
     *
     * @param $mixed value to transform to TypeInterface instance
     *
     * @return TypeInterface
     */
    public static function valueOf($mixed);
}
