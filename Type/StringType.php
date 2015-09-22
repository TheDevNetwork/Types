<?php

namespace Tdn\PhpTypes\Type;

use Doctrine\Common\Inflector\Inflector;
use Stringy\Stringy;
use Tdn\PhpTypes\Exception\InvalidTransformationException;

/**
 * Class StringType.
 */
class StringType extends Stringy implements TypeInterface
{
    /**
     * Mainly here for type hinting purposes...
     *
     * @param string $str
     * @param string $encoding
     *
     * @return static
     */
    public static function create($str = '', $encoding = 'UTF-8')
    {
        return parent::create($str, $encoding);
    }

    /**
     * Pluralizes the string.
     *
     * @return static
     */
    public function pluralize()
    {
        return static::create($this->getInflector()->pluralize((string) $this->str), $this->encoding);
    }

    /**
     * Singularizes the string.
     *
     * @return static
     */
    public function singularize()
    {
        return static::create($this->getInflector()->singularize((string) $this->str), $this->encoding);
    }

    /**
     * Returns position of the first occurrence of subStr null if not present.
     *
     * @param string $subStr        Substring
     * @param int|null $offset           Chars to offset from start
     * @param bool $caseSensitive   Enable case sensitivity
     *
     * @return int
     */
    public function strpos($subStr, $offset = null, $caseSensitive = false)
    {
        return ($caseSensitive) ?
            mb_strpos($this->str, $subStr, $offset, $this->encoding) :
            mb_stripos($this->str, $subStr, $offset, $this->encoding);
    }

    /**
     * Returns position of the last occurrence of subStr null if not present.
     *
     * @param string $subStr        Substring
     * @param int|null $offset           Chars to offset from start
     * @param bool $caseSensitive   Enable case sensitivity
     *
     * @return int
     */
    public function strrpos($subStr, $offset = null, $caseSensitive = false)
    {
        return ($caseSensitive) ?
            mb_strrpos($this->str, $subStr, $offset, $this->encoding) :
            mb_strripos($this->str, $subStr, $offset, $this->encoding);
    }

    /**
     * @return Inflector
     */
    public function getInflector()
    {
        return new Inflector();
    }

    /**
     * @return string The current value of the $str property
     */
    public function __toString()
    {
        return (string) $this->str;
    }

    /**
     * @see StringType::from
     */
    public static function valueOf($mixed)
    {
        return static::from($mixed);
    }

    /**
     * @param mixed $mixed
     *
     * @return static
     */
    public static function from($mixed)
    {
        switch (strtolower(gettype($mixed))) {
            case 'boolean':
                return new static((($mixed) ? 'true' : 'false'));
            case 'integer':
            case 'float':
            case 'double':
            case 'string':
                return new static((string) $mixed);
            case 'array':
                return new static(implode(', ', $mixed));
            case 'object':
                if (method_exists($mixed, '__toString')) {
                    return new static((string) $mixed);
                }

                throw new InvalidTransformationException(
                    sprintf(
                        'Could not transform %s to string.',
                        get_class($mixed)
                    )
                );
            case 'resource':
                if (false !== $type = get_resource_type($mixed)) {
                    return new static($type);
                }

                throw new InvalidTransformationException('Could not transform resource to string.');
            case 'null':
                return new static('');
            default:
                throw new InvalidTransformationException('Could not transform unknown type to string');
        }
    }
}
