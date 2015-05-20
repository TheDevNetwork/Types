<?php

namespace Tdn\PhpTypes\Type;

use Doctrine\Common\Inflector\Inflector;
use Stringy\Stringy;

/**
 * Class String
 * @package Tdn\PhpTypes\Type
 */
class String extends Stringy
{
    /**
     * Mainly here for type hinting purposes...
     *
     * @param mixed $str
     * @param string $encoding
     *
     * @throws \InvalidArgumentException when $str is not a string.
     *
     * @return \Tdn\PhpTypes\Type\String
     */
    public static function create($str, $encoding = 'UTF-8')
    {
        $type = gettype($str);
        if ($type !== 'string') {
            throw new \InvalidArgumentException(
                sprintf(
                    'Expected string got %s instead.',
                    $type
                )
            );
        }

        return new static($str, $encoding);
    }

    /**
     * Pluralizes the string.
     *
     * @return self
     */
    public function pluralize()
    {
        return self::create($this->getInflector()->pluralize((string) $this->str), $this->encoding);
    }

    /**
     * Singularizes the string.
     *
     * @return self
     */
    public function singularize()
    {
        return self::create($this->getInflector()->singularize((string) $this->str), $this->encoding);
    }

    /**
     * Returns substring from beginning until first instance of subsStr.
     *
     * @param string $subStr
     * @param bool $excluding
     * @param bool $caseSensitive
     *
     * @return \Tdn\PhpTypes\Type\String
     */
    public function subStrUntil($subStr, $excluding = false, $caseSensitive = false)
    {
        $fromSubStr = $this->str[0];
        return $this->subStrBetween($fromSubStr, $subStr, false, $excluding, $caseSensitive);
    }

    /**
     * Returns substring from first instance of subStr to end of string.
     * @param $subStr
     * @param bool $excluding
     * @param bool $caseSensitive
     *
     * @return \Tdn\PhpTypes\Type\String
     */
    public function subStrAfter($subStr, $excluding = false, $caseSensitive = false)
    {
        return $this->subStrBetween($subStr, null, $excluding, false, $caseSensitive);
    }

    /**
     * Returns substring between fromSubStr to toSubStr. End of string if toSubStr is not set.
     *
     * @param string $fromSubStr
     * @param string $toSubStr
     * @param bool $excludeFromSubStr
     * @param bool $excludeToSubStr
     * @param bool $caseSensitive
     * @return self
     */
    public function subStrBetween(
        $fromSubStr,
        $toSubStr = '',
        $excludeFromSubStr = false,
        $excludeToSubStr = false,
        $caseSensitive = false
    ) {
        $fromIndex = 0;
        $toIndex = mb_strlen($this->str);
        $str = self::create($this->str);
        if ($str->contains($fromSubStr)) {
            $fromIndex = $this->strpos($fromSubStr, 0, $caseSensitive);
            $fromIndex = ($excludeFromSubStr) ? $fromIndex + mb_strlen($fromSubStr, $this->encoding) : $fromIndex;

            if ($fromIndex < 0) {
                throw new \LogicException('To cannot be before from.');
            }

            if (!empty($toSubStr) && $str->contains($toSubStr)) {
                $toIndex = $this->strpos($toSubStr, $fromIndex, $caseSensitive);
                $toIndex = ($excludeToSubStr) ?
                    $toIndex - $fromIndex :  ($toIndex - $fromIndex) + mb_strlen($toSubStr, $this->encoding);
            }
        }

        return ($toSubStr) ? $str->substr($fromIndex, $toIndex) : $str->substr($fromIndex);
    }

    /**
     * Returns position of the first occurrence of subStr null if not present.
     * @param $subStr
     * @param int $start
     * @param bool $caseSensitive
     *
     * @return int
     */
    public function strpos($subStr, $start = 0, $caseSensitive = false)
    {
        return ($caseSensitive) ?
            mb_strpos($this->str, $subStr, $start, $this->encoding) :
            mb_stripos($this->str, $subStr, $start, $this->encoding);
    }

    /**
     * Returns position of the last occurrence of subStr null if not present.
     * @param $subStr
     * @param int $offset
     * @param bool $caseSensitive
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
     * Gets current pad length. Skips newlines.
     * Ensures that all lines passed are indented equally, otherwise fails.
     * Returns the number instances that $padStr occurs.
     *
     * @param string $padStr
     * @return int
     */
    public function getPadSize($padStr = ' ')
    {
        $str = self::create($this->str);
        $counters = [];
        $position = 0;
        $line     = 0;

        foreach ($str as $letter) {
            //No need to add support for tabs since we want to follow PSR2.
            if ($letter == $padStr) {
                //If we're in the middle of a string, do not count the space.
                if (isset($str[$position - 1]) && ($str[$position - 1] != $padStr && $str[$position - 1] != "\n")) {
                    continue;
                }

                $counters[$line]++;
            }

            //Make sure not to initiate a new array
            if ($letter == "\n" && isset($str[$position + 1])) {
                $line++;
                $counters[$line] = 0;
            }

            $position++;
        }

        if (count(array_unique($counters)) > 1) {
            throw new \RuntimeException('String passed is not correctly indented. Indentation is not consistent.');
        }

        return array_pop($counters);
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
}
