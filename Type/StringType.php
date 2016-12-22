<?php

declare (strict_types = 1);

namespace Tdn\PhpTypes\Type;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\Common\Collections\Collection as CollectionInterface;
use Stringy\Stringy;
use Tdn\PhpTypes\Exception\InvalidTypeCastException;
use Tdn\PhpTypes\Type\Traits\Boxable;
use Tdn\PhpTypes\Type\Traits\Transmutable;
use Tdn\PhpTypes\Exception\InvalidTransformationException;

/**
 * Class StringType.
 *
 * A StringType is a TypeInterface implementation that wraps around a regular PHP string.
 * This object extends Stringy.
 *
 * {@inheritdoc}
 */
class StringType extends Stringy implements TransmutableTypeInterface, ValueTypeInterface
{
    use Transmutable;
    use Boxable;

    /**
     * StringType constructor.
     * Explicitly removing parent constructor. Type conversion should be done through StringType::from($mixed).
     *
     * @param string      $str
     * @param string|null $encoding
     */
    public function __construct(string $str, string $encoding = null)
    {
        $this->str = $str;
        $this->encoding = $encoding ?: \mb_internal_encoding();
        //Explicitly not calling parent construct.
    }

    /**
     * {@inheritdoc}
     *
     * @return string|int|float|bool|array
     */
    public function __invoke(int $toType = Type::STRING)
    {
        switch ($toType) {
            case Type::STRING:
                return $this->str;
            case Type::INT:
                if (is_numeric((string) $this)) {
                    return IntType::valueOf($this)->get();
                }

                break;
            case Type::FLOAT:
                if (is_numeric((string) $this)) {
                    return FloatType::valueOf($this)->get();
                }

                break;
            case Type::ARRAY:
                if ($this->contains(',')) {
                    return $this->explode(',')->toArray();
                }

                break;
            case Type::BOOL:
                return BooleanType::valueOf($this)->get();
        }

        throw new InvalidTypeCastException(static::class, $this->getTranslatedType($toType));
    }

    /**
     * @return string
     */
    public function get()
    {
        return (string) $this;
    }

    /**
     * Creates a new static instance from string.
     *
     * Mainly here for code completion purposes...
     *
     * @param string $str
     * @param string $encoding
     *
     * @return StringType
     */
    public static function create($str = '', $encoding = 'UTF-8') : StringType
    {
        return new static($str, $encoding);
    }

    /**
     * Explodes current instance into a collection object.
     *
     * @param string $delimiter
     * @param int    $limit     default PHP_INT_MAX
     *
     * @return Collection
     */
    public function explode(string $delimiter, int $limit = PHP_INT_MAX) : Collection
    {
        return new Collection(explode($delimiter, $this->regexReplace('[[:space:]]', '')->str, $limit));
    }

    /**
     * Pluralizes the string.
     *
     * @return StringType
     */
    public function pluralize() : StringType
    {
        return static::create($this->getInflector()->pluralize((string) $this->str), $this->encoding);
    }

    /**
     * Singularizes the string.
     *
     * @return StringType
     */
    public function singularize() : StringType
    {
        return static::create($this->getInflector()->singularize((string) $this->str), $this->encoding);
    }

    /**
     * Returns position of the first occurrence of subStr null if not present.
     *
     * @param string $subStr        Substring
     * @param int    $offset        Chars to offset from start
     * @param bool   $caseSensitive Enable case sensitivity
     *
     * @return IntType
     */
    public function strpos(string $subStr, int $offset = 0, bool $caseSensitive = false) : IntType
    {
        $res = ($caseSensitive) ?
            mb_strpos($this->str, $subStr, $offset, $this->encoding) :
            mb_stripos($this->str, $subStr, $offset, $this->encoding);

        return new IntType($res);
    }

    /**
     * Returns position of the last occurrence of subStr null if not present.
     *
     * @param string $subStr        Substring
     * @param int    $offset        Chars to offset from start
     * @param bool   $caseSensitive Enable case sensitivity
     *
     * @return IntType
     */
    public function strrpos(string $subStr, int $offset = 0, bool $caseSensitive = false) : IntType
    {
        $res = ($caseSensitive) ?
            mb_strrpos($this->str, $subStr, $offset, $this->encoding) :
            mb_strripos($this->str, $subStr, $offset, $this->encoding);

        return new IntType($res);
    }

    /**
     * @return string The current value of the $str property
     */
    public function __toString() : string
    {
        return (string) $this->str;
    }

    /**
     * Override trait to remove spaces.
     *
     * @return BooleanType
     */
    public function toBoolType() : BooleanType
    {
        return BooleanType::valueOf($this->regexReplace('[[:space:]]', '')->str);
    }

    /**
     * @return BooleanType
     */
    public function toBoolean() : BooleanType
    {
        return $this->toBoolType();
    }

    /**
     * @return DateTimeType
     */
    public function toDateTime() : DateTimeType
    {
        return DateTimeType::valueOf($this);
    }

    /**
     * {@inheritdoc}
     *
     * @return StringType
     */
    public static function valueOf($mixed) : StringType
    {
        return new static(self::asString($mixed));
    }

    /**
     * @return Inflector
     */
    private function getInflector() : Inflector
    {
        return new Inflector();
    }

    /**
     * Returns a mixed variable as a string.
     *
     * @param mixed $mixed
     *
     * @return string
     */
    private static function asString($mixed) : string
    {
        if ($mixed instanceof ValueTypeInterface) {
            $mixed = $mixed->get();
        }

        if ($mixed instanceof CollectionInterface) {
            //Handle as array.
            $mixed = $mixed->toArray();
        }

        $type = strtolower(gettype($mixed));
        switch ($type) {
            case 'string':
            case 'integer':
            case 'float':
            case 'double':
                return (string) $mixed;
            case 'boolean':
                return ($mixed) ? 'true' : 'false';
            case 'array':
                return implode(', ', $mixed);
            case 'object':
                if (method_exists($mixed, '__toString')) {
                    return (string) $mixed;
                }

                throw new InvalidTransformationException($type, static::class);
            case 'resource':
                return get_resource_type($mixed);
            case 'null':
            default:
                throw new InvalidTransformationException($type, static::class);
        }
    }
}
