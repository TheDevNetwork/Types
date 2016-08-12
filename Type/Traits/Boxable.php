<?php

declare (strict_types = 1);

namespace Tdn\PhpTypes\Type\Traits;

use Tdn\PhpTypes\Memory\Memory;
use Tdn\PhpTypes\Type\Type;

/**
 * Trait Boxable.
 */
trait Boxable
{
    /**
     * Address to pointer in Memory::collection.
     *
     * @var string
     */
    private $memoryAddress = null;

    /**
     * Boxes a variable to a specific type, including future primitive reassignment.
     * Optionally takes value or instance of the variable.
     *
     * BooleanType::box($boolVar, false);// $boolVar instanceof BooleanType with value false.
     *
     * $boolVar = true;// $boolVar is still instanceof BooleanType with new value of true.
     *
     * BooleanType::box($myOtherBool, new BooleanType(true));//$myOtherBool is instanceof BooleanType, true value.
     *
     * $myOtherBool = false;//$myOtherBool is still instanceof BooleanType, false value.
     *
     * $myOtherBool = 1;//Throws TypeError.
     *
     * Caveats:
     *
     * If more than one argument should be passed to constructor, then an instance should be passed explicitly instead
     * of a primitive for $value argument.
     *
     * @param null  &$pointer Anmpty variable to box (the pointer).
     * @param mixed $value    The primitive value to pass the constructor OR an instance of the type.
     *
     * @throws \TypeError when an invalid value is passed as second argument.
     */
    final public static function box(&$pointer, $value = null)
    {
        if ($pointer !== null) {
            throw new \LogicException(
                sprintf(
                    'The identifier of type %s is defined more than once. ' .
                    'First argument of %s() must be null or undefined.',
                    gettype($pointer),
                    __METHOD__
                )
            );
        }

        try {
            if ($value instanceof static) {
                $pointer = clone $value;
            } else {
                if (!method_exists(static::class, '__construct')) {
                    throw new \LogicException(
                        sprintf(
                            '%s implemented but no constructor method found in class: %s',
                            self::class,
                            static::class
                        )
                    );
                }

                $pointer = $value !== null ? new static($value) : new static();
            }
        } catch (\TypeError $e) {
            $message = sprintf(
                '%s. Argument can be instance of %s or scalar equivalent. ',
                $e->getMessage(),
                static::class
            );

            throw new \TypeError($message, $e->getCode(), $e);
        }

        $pointer->memoryAddress = Memory::getNewAddress($pointer);
    }

    /**
     * Runs when a variable is reassigned or destroyed with $pointer = null;.
     * Basically overloads the assignment operator when a specific pointer has been boxed.
     */
    final public function __destruct()
    {
        if ($this->memoryAddress === null) {
            return;
        }

        $pointer = &Memory::getPointer($this->memoryAddress);
        $value = $pointer;

        if ($value !== $this && $value !== null) {
            $pointer = null;
            static::box($pointer, $value);
        }

        Memory::shouldFree($this->memoryAddress);
    }

    /**
     * @param int|null $type
     *
     * @return string
     */
    protected function getTranslatedType(int $type = null) : string
    {
        if ($type !== null && !array_key_exists($type, $this->getSupportedTypes())) {
            throw new \OutOfBoundsException(
                sprintf('Type %s not found. Valid types are %s.', $type, implode(', ', $this->getSupportedTypes()))
            );
        }

        return $this->getSupportedTypes()[$type];
    }

    /**
     * @return array<int,string>
     */
    private function getSupportedTypes() : array
    {
        return [
            Type::STRING => 'string',
            Type::BOOL => 'bool',
            Type::INT => 'int',
            Type::FLOAT => 'float',
            Type::ARRAY => 'array',
        ];
    }
}
