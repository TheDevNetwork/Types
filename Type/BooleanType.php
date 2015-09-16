<?php

namespace Tdn\PhpTypes\Type;

use Tdn\PhpTypes\Exception\InvalidTransformationException;

/**
 * Class BooleanType.
 */
class BooleanType implements TypeInterface
{
    /**
     * @var bool
     */
    private $value;

    /**
     * @param mixed $bool
     */
    public function __construct($bool)
    {
        if (!is_bool($bool)) {
            $bool = self::asBool($bool);
        }

        $this->value = $bool;
    }

    /**
     * @return bool
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @deprecated
     * @see BooleanType::from
     */
    public static function valueOf($mixed)
    {
        return static::from($mixed);
    }

    /**
     * @param mixed $mixed
     * @return static
     */
    public static function from($mixed)
    {
        return new static(self::asBool($mixed));
    }

    /**
     * @param $mixed
     *
     * @return bool
     */
    private static function asBool($mixed)
    {
        switch (strtolower(gettype($mixed))) {
            case 'boolean':
                return $mixed;
            case 'string':
                return (strtolower($mixed) == 'true') ? true : false;
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

    /**
     * @return bool
     */
    public function __invoke()
    {
        return $this->getValue();
    }
}
