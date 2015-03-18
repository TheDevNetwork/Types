<?php

namespace Tdn\PhpTypes\Type;

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

    protected static $plural = [
        'rules' => [
            '/(s)tatus$/i' => '\1tatuses',
            '/(quiz)$/i' => '\1zes',
            '/^(ox)$/i' => '\1\2en',
            '/([m|l])ouse$/i' => '\1ice',
            '/(matr|vert|ind)(ix|ex)$/i' => '\1ices',
            '/(x|ch|ss|sh)$/i' => '\1es',
            '/([^aeiouy]|qu)y$/i' => '\1ies',
            '/(hive)$/i' => '\1s',
            '/(?:([^f])fe|([lre])f)$/i' => '\1\2ves',
            '/sis$/i' => 'ses',
            '/([ti])um$/i' => '\1a',
            '/(p)erson$/i' => '\1eople',
            '/(m)an$/i' => '\1en',
            '/(c)hild$/i' => '\1hildren',
            '/(buffal|tomat)o$/i' => '\1\2oes',
            '/(alumn|bacill|cact|foc|fung|nucle|radi|stimul|syllab|termin|vir)us$/i' => '\1i',
            '/us$/i' => 'uses',
            '/(alias)$/i' => '\1es',
            '/(ax|cris|test)is$/i' => '\1es',
            '/s$/' => 's',
            '/^$/' => '',
            '/$/' => 's',
        ],
        'uninflected' => [
            '.*[nrlm]ese',
            '.*data',
            '.*deer',
            '.*fish',
            '.*measles',
            '.*ois',
            '.*pox',
            '.*sheep',
            'people',
            'feedback',
            'stadia'
        ],
        'irregular' => [
            'atlas' => 'atlases',
            'beef' => 'beefs',
            'brief' => 'briefs',
            'brother' => 'brothers',
            'cafe' => 'cafes',
            'child' => 'children',
            'cookie' => 'cookies',
            'corpus' => 'corpuses',
            'cow' => 'cows',
            'ganglion' => 'ganglions',
            'genie' => 'genies',
            'genus' => 'genera',
            'graffito' => 'graffiti',
            'hoof' => 'hoofs',
            'loaf' => 'loaves',
            'man' => 'men',
            'money' => 'monies',
            'mongoose' => 'mongooses',
            'move' => 'moves',
            'mythos' => 'mythoi',
            'niche' => 'niches',
            'numen' => 'numina',
            'occiput' => 'occiputs',
            'octopus' => 'octopuses',
            'opus' => 'opuses',
            'ox' => 'oxen',
            'penis' => 'penises',
            'person' => 'people',
            'sex' => 'sexes',
            'soliloquy' => 'soliloquies',
            'testis' => 'testes',
            'trilby' => 'trilbys',
            'turf' => 'turfs',
            'potato' => 'potatoes',
            'hero' => 'heroes',
            'tooth' => 'teeth',
            'goose' => 'geese',
            'foot' => 'feet'
        ]
    ];

    /**
     * Singular inflector rules
     *
     * @var array
     */
    protected static $singular = [
        'rules' => [
            '/(s)tatuses$/i' => '\1\2tatus',
            '/^(.*)(menu)s$/i' => '\1\2',
            '/(quiz)zes$/i' => '\\1',
            '/(matr)ices$/i' => '\1ix',
            '/(vert|ind)ices$/i' => '\1ex',
            '/^(ox)en/i' => '\1',
            '/(alias)(es)*$/i' => '\1',
            '/(alumn|bacill|cact|foc|fung|nucle|radi|stimul|syllab|termin|viri?)i$/i' => '\1us',
            '/([ftw]ax)es/i' => '\1',
            '/(cris|ax|test)es$/i' => '\1is',
            '/(shoe)s$/i' => '\1',
            '/(o)es$/i' => '\1',
            '/ouses$/' => 'ouse',
            '/([^a])uses$/' => '\1us',
            '/([m|l])ice$/i' => '\1ouse',
            '/(x|ch|ss|sh)es$/i' => '\1',
            '/(m)ovies$/i' => '\1\2ovie',
            '/(s)eries$/i' => '\1\2eries',
            '/([^aeiouy]|qu)ies$/i' => '\1y',
            '/(tive)s$/i' => '\1',
            '/(hive)s$/i' => '\1',
            '/(drive)s$/i' => '\1',
            '/([le])ves$/i' => '\1f',
            '/([^rfoa])ves$/i' => '\1fe',
            '/(^analy)ses$/i' => '\1sis',
            '/(analy|diagno|^ba|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => '\1\2sis',
            '/([ti])a$/i' => '\1um',
            '/(p)eople$/i' => '\1\2erson',
            '/(m)en$/i' => '\1an',
            '/(c)hildren$/i' => '\1\2hild',
            '/(n)ews$/i' => '\1\2ews',
            '/eaus$/' => 'eau',
            '/^(.*us)$/' => '\\1',
            '/s$/i' => ''
        ],
        'uninflected' => [
            '.*data',
            '.*[nrlm]ese',
            '.*deer',
            '.*fish',
            '.*measles',
            '.*ois',
            '.*pox',
            '.*sheep',
            '.*ss',
            'feedback'
        ],
        'irregular' => [
            'foes' => 'foe',
        ]
    ];

    /**
     * Words that should not be inflected
     *
     * @var array
     */
    protected static $uninflected = [
        'Amoyese',
        'bison',
        'Borghese',
        'bream',
        'breeches',
        'britches',
        'buffalo',
        'cantus',
        'carp',
        'chassis',
        'clippers',
        'cod',
        'coitus',
        'Congoese',
        'contretemps',
        'corps',
        'debris',
        'diabetes',
        'djinn',
        'eland',
        'elk',
        'equipment',
        'Faroese',
        'flounder',
        'Foochowese',
        'gallows',
        'Genevese',
        'Genoese',
        'Gilbertese',
        'graffiti',
        'headquarters',
        'herpes',
        'hijinks',
        'Hottentotese',
        'information',
        'innings',
        'jackanapes',
        'Kiplingese',
        'Kongoese',
        'Lucchese',
        'mackerel',
        'Maltese',
        '.*?media',
        'mews',
        'moose',
        'mumps',
        'Nankingese',
        'news',
        'nexus',
        'Niasese',
        'Pekingese',
        'Piedmontese',
        'pincers',
        'Pistoiese',
        'pliers',
        'Portuguese',
        'proceedings',
        'rabies',
        'research',
        'rice',
        'rhinoceros',
        'salmon',
        'Sarawakese',
        'scissors',
        'sea[- ]bass',
        'series',
        'Shavese',
        'shears',
        'siemens',
        'species',
        'swine',
        'testes',
        'trousers',
        'trout',
        'tuna',
        'Vermontese',
        'Wenchowese',
        'whiting',
        'wildebeest',
        'Yengeese'
    ];

    /**
     * Pluralizes the string.
     *
     * @return self
     */
    public function pluralize()
    {
        if (preg_match(
            '/(.*)\\b(' . '(?:' . implode('|', array_keys(self::$plural['irregular'])) . ')' . ')$/i',
            $this->str,
            $regs
        )) {
            $this->str = $regs[1] . substr($this->str, 0, 1) .
                substr(self::$plural['irregular'][strtolower($regs[2])], 1);

            return self::create($this->str);
        }

        if (preg_match('/^(' . '(?:' . implode('|', self::$plural['uninflected']) . ')' . ')$/i', $this->str, $regs)) {
            return self::create($this->str);
        }

        foreach (self::$plural['rules'] as $rule => $replacement) {
            if (preg_match($rule, $this->str)) {
                return self::create(preg_replace($rule, $replacement, $this->str));
            }
        }

        return self::create($this->str, $this->encoding);
    }

    /**
     * Singularizes the string.
     *
     * @return self
     */
    public function singularize()
    {
        if (preg_match(
            '/(.*)\\b(' . '(?:' . implode('|', array_keys(self::$singular['irregular'])) . ')' . ')$/i',
            $this->str,
            $regs
        )) {
            $this->str = $regs[1] . substr($this->str, 0, 1) .
                substr(self::$singular['irregular'][strtolower($regs[2])], 1);

            return self::create($this->str);
        }

        if (preg_match(
            '/^(' . '(?:' . implode('|', self::$singular['uninflected']) . ')' . ')$/i',
            $this->str,
            $regs
        )) {
            return self::create($this->str);
        }

        foreach (self::$singular['rules'] as $rule => $replacement) {
            if (preg_match($rule, $this->str)) {
                return self::create(preg_replace($rule, $replacement, $this->str));
            }
        }

        return self::create($this->str, $this->encoding);
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
    public function subStrBetween($fromSubStr, $toSubStr = '', $excludeFromSubStr = false, $excludeToSubStr = false, $caseSensitive = false)
    {
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
                $toIndex = ($excludeToSubStr) ? $toIndex - $fromIndex :  ($toIndex - $fromIndex) + mb_strlen($toSubStr, $this->encoding);
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
     * @return string The current value of the $str property
     */
    public function __toString()
    {
        return (string) $this->str;
    }
}
