<?php

declare(strict_types=1);

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
    final public static function box(&$pointer, $value = null)
    {
        if ($pointer !== null) {
            throw new \LogicException(
                sprintf(
                    'The identifier of type %s is defined more than once. '.
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
                $pointer = $value !== null ? new static($value) : new static();
            }
        } catch (\TypeError $e) {
            $message = sprintf(
                '%s. Argument can be instance of %s or scalar equivalent.',
                $e->getMessage(),
                static::class
            );

            throw new \TypeError($message, $e->getCode(), $e);
        }

        $pointer->memoryAddress = Memory::getNewAddress($pointer);
    }

    /**
     * Runs when a variable is reassigned or destroyed with $pointer = null;.
     * Basically overloads the assignment operator when a specific pointer has been boxed to return a new instance
     * of the previous type with the new assigned value.
     */
    final public function __destruct()
    {
        if ($this->memoryAddress === null) {
            return;
        }

        $pointer = &Memory::getPointer($this->memoryAddress);
        $value = $pointer;

        if ($value !== $this && $value !== null) {
            try {
                // Clear pointer before attempting to box new value.
                $pointer = null;
                static::box($pointer, $value);
            } catch (\TypeError $e) {
                // Reset the pointer to the previous value and re throw exception.
                // This will allow the variable to remain boxed, the exception to be caught, and handled appropriately.
                $pointer = clone $this;

                throw $e;
            }
        }

        Memory::shouldFree($this->memoryAddress);
    }

    /**
     * Translates type to cast front int to string representation.
     *
     * @param int|null $type
     *
     * @return string
     */
    protected function getTranslatedType(int $type = null): string
    {
        $supportedTypes = [
            Type::STRING => 'string',
            Type::BOOL => 'bool',
            Type::INT => 'int',
            Type::FLOAT => 'float',
            Type::ARRAY => 'array',
        ];

        if ($type !== null && !array_key_exists($type, $supportedTypes)) {
            throw new \OutOfBoundsException(
                sprintf('Type %s not found. Valid types are %s.', $type, implode(', ', $supportedTypes))
            );
        }

        return $supportedTypes[$type];
    }
}
