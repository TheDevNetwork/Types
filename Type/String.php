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
     * @return \Tdn\PhpTypes\Type\String
     */
    public static function create($str, $encoding = 'UTF-8')
    {
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
            $fromIndex = ($caseSensitive) ?
                mb_strpos($this->str, $fromSubStr, 0, $this->encoding) :
                mb_stripos($this->str, $fromSubStr, 0, $this->encoding);
            $fromIndex = ($excludeFromSubStr) ? $fromIndex + mb_strlen($fromSubStr, $this->encoding) : $fromIndex;

            if ($fromIndex < 0) {
                throw new \LogicException('To cannot be before from.');
            }

            if (!empty($toSubStr) && $str->contains($toSubStr)) {
                $toIndex = ($caseSensitive) ?
                    mb_stripos($this->str, $toSubStr, $fromIndex, $this->encoding) :
                    mb_strpos($this->str, $toSubStr, $fromIndex, $this->encoding);
                $toIndex = ($excludeToSubStr) ?
                    $toIndex - $fromIndex :  ($toIndex - $fromIndex) + mb_strlen($toSubStr, $this->encoding);
            }
        }

        return ($toSubStr) ? $str->substr($fromIndex, $toIndex) : $str->substr($fromIndex);
    }

    /**
     * Returns position of the first occurance of subStr null if not present.
     * @param $subStr
     * @param int $start
     * @param bool $caseSensitive
     *
     * @return int
     */
    public function strpos($subStr, $start = 0, $caseSensitive = false)
    {
        $res = ($caseSensitive) ?
            mb_strpos($this->str, addslashes($subStr), $start, $this->encoding) :
            mb_stripos($this->str, addslashes($subStr), $start, $this->encoding);

        return $res;
    }

    /**
     * Adds a predefined number of identation indentation spaces to string.
     * If newlines are found, it will add number of spaces before each newline.
     *
     * @param int $numSpaces
     * @param int $padType
     * @param bool $perNewline
     *
     * @return self
     */
    public function addIndent($numSpaces = null, $padType = STR_PAD_LEFT, $perNewline = false)
    {
        $numSpaces = ($numSpaces) ? $numSpaces : 4;
        $str = self::create($this->str);
        $spaces = str_repeat(' ', $numSpaces);
        if ($str->contains("\n") !== false && $perNewline) {
            $lines = explode("\n", trim($this->str));
            foreach ($lines as &$line) {
                $line = $this->lineStrPad($line, $padType, $spaces);
            }
            return self::create(implode("\n", $lines));
        }

        return String::create($this->lineStrPad($str, $padType, $spaces));
    }

    /**
     * Gets current indentation length. Skips newlines.
     * Stops as soon as a nonspace is detected. Per line.
     *
     * @param null $str
     * @return int
     */
    public function getIndentSize($str = null)
    {
        $str = ($str) ? $str : self::create($this->str);
        if ((string) $str[0] == "\n") {
            $str = $str->substr(1);
            $this->getIndentSize($str);
        }
        $counter = 0;
        foreach ($str as $letter) {
            if ($letter == ' ') {
                $counter++;
            } else {
                break;
            }
        }

        return $counter;
    }

    /**
     * @param $line
     * @param $padType
     * @param $spaces
     * @return string
     */
    private function lineStrPad($line, $padType, $spaces)
    {
        switch($padType) {
            case STR_PAD_LEFT:
                $line = $spaces . $line;
                break;
            case STR_PAD_BOTH:
                $line = $spaces . $line . $spaces;
                break;
            case STR_PAD_RIGHT:
                $line = $line . $spaces;
        }

        return $line;
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
