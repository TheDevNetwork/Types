<?php

declare (strict_types = 1);

namespace Tdn\PhpTypes\Type;

use Tdn\PhpTypes\Exception\InvalidTypeCastException;

/**
 * Interface BoxedTypeInterface.
 */
interface BoxedTypeInterface
{
    /**
     * Boxes a variable to a specific type, including future primitive reassignment.
     * Optionally takes value or instance of the variable.
     *
     * BooleanType::box($boolVar, false);// $boolVar instanceof BooleanType with value false.
     * $boolVar = true;// $boolVar is still instanceof BooleanType with new value of true.
     *
     * BooleanType::box($myOtherBool, new BooleanType(true));//$myOtherBool is instanceof BooleanType, true value.
     * $myOtherBool = false;//$myOtherBool is still instanceof BooleanType, false value.
     * $myOtherBool = 1;//Throws TypeError.
     *
     * Caveats:
     * If more than one argument should be passed to constructor, then an instance should be passed explicitly instead
     * of a primitive for $value argument.
     *
     * @param null       &$pointer Anmpty variable to box (the pointer).
     * @param null|mixed $value    The primitive value to pass the constructor OR an instance of the type.
     *
     * @throws \TypeError when an invalid value is passed as second argument.
     */
    public static function box(&$pointer, $value = null);

    /**
     * Returns the primitive value of current instance casted to specified type.
     * Defaults to logical primitive based on type.
     *
     * @param int|null $toType Type to cast to. Default: varies.
     *
     * @throws InvalidTypeCastException when casted to an unsupported type.
     *
     * @return bool|float|int|string|array|DateTime
     */
    public function __invoke(int $toType = null);
}
