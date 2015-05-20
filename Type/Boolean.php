<?php

namespace Tdn\PhpTypes\Type;

use Tdn\PhpTypes\Exception\InvalidTransformationException;

/**
 * Class Boolean
 * @package Tdn\PhpTypes\Type
 */
class Boolean implements TypeInterface
{
    /**
     * @var bool
     */
    private $value;

    /**
     * @param $bool
     */
    public function __construct($bool)
    {
        $this->value = static::getValue($bool);
    }

    /**
     * @param mixed $mixed
     * @return static
     */
    public static function valueOf($mixed)
    {
        return new static(static::getValue($mixed));
    }

    /**
     * @param $mixed
     *
     * @return bool
     */
    private static function getValue($mixed)
    {
        switch (strtolower(gettype($mixed))) {
            case 'boolean':
                return $mixed;
            case 'string':
                return (strtolower($mixed) == "true") ? true : false;
            case 'null':
            case 'object':
            case 'resource':
                return ($mixed === null || $mixed === false) ? false : true;
            //Use booleans, do not use any of these if the variable should be a boolean...
            case 'array':
            case 'integer':
            case 'float':
            case 'double':
            default:
                throw new InvalidTransformationException(
                    sprintf(
                        'Could not transform %s to boolean.',
                        gettype($mixed)
                    )
                );
        }
    }
}
