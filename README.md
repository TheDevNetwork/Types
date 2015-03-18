# PhpTypes
A primitive wrappers library for PHP.

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/5d4f02af-7c43-4079-bcb0-9d57439a9a3f/big.png)](https://insight.sensiolabs.com/projects/5d4f02af-7c43-4079-bcb0-9d57439a9a3f)

## Roadmap
- [x] String
- [ ] Bool
- [ ] Int
- [ ] Float
- [ ] Enum

### String
Currently this class extends [danielstjules/Stringy](https://github.com/danielstjules/Stringy) and provides the following additional methods

* [pluralize](#pluralize)
* [singularize](#singularize)
* [subStrUntil](#substruntil)
* [subStrAfter](#substrafter)
* [subStrBetween](#substrfromto)
* [strpos](#strpos)
* [addIndent](#addindent)
* [getIndentSize](#getindentsize)

#### pluralize
$string->pluralize()

Returns the word in plural form.

```php
$word = String::create('syllabus')->pluralize();
echo $word; //syllabi
```

#### singularize
$string->singularize()

Returns the word in singular form.

```php
$word = String::create('walruses')->singularize();
echo $word; //walrus
```

#### subStrUntil
$string->subStrUntil(string $subString[, bool $excluding = false[, bool $caseSensitive = false]])

Returns the string until the first instance of subString indicated. Optionally excludes substring. Optionally case sensitive.

```php
$sentence = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';

$subSentence = String::create($sentence)->subStrUntil(',', true);
echo $subSentence; //Lorem ipsum dolor sit amet

$subSentence = String::create($sentence)->subStrUntil(',');
echo $subSentence; //Lorem ipsum dolor sit amet,
```

#### subStrAfter
$string->subStrAfter(string $subString[, bool $excluding = false[, bool $caseSensitive = false]])

Returns the string after the first instance of subString indicated. Optionally excludes substring. Optionally case sensitive.

```php
$sentence = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';

$subSentence = String::create($sentence)->subStrAfter('elit, ', true);
echo $subSentence; //sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.

$subSentence = String::create($sentence)->subStrAfter('elit, ');
echo $subSentence; //elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
```

#### subStrBetween
$string->subStrBetween(string $fromSubStr[, string $toSubStr = null[, bool $excludeFromSubStr = false[, bool $excludeToSubStr = false[, bool $caseSensitive = false]]]])

Returns substring between first instance of fromSubStr to first instance of toSubStr or end of string if toSubStr is not set. Optionally excludes fromSubStr. Optionally exclude toSubStr. Optionally case sensitive.

```php
$sentence = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';

$subSentence = String::create($sentence)->subStrBetween('amet, ', ', sed', true, true);
echo $subSentence; //consectetur adipiscing elit

$subSentence = String::create($sentence)->subStrBetween('amet, ', ', sed', false, true);
echo $subSentence; //amet, consectetur adipiscing elit

$subSentence = String::create($sentence)->subStrBetween('amet, ', ', sed', true, false);
echo $subSentence; //consectetur adipiscing elit, sed

$subSentence = String::create($sentence)->subStrBetween('amet, ', ', sed');
echo $subSentence; //amet, consectetur adipiscing elit, sed
```
#### strpos
$string->strpos(string $subStr[, int $start = 0[, bool $caseSensitive = false]])

Returns position of the first occurance of subStr null if not present. Optional start index. Optionally case sensitive.

```php
$sentence = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';

$position = String::create($sentence)->strpos('ipsum');
echo $position; //6
```

#### addIndent
$string->addIdent([int $numSpaces = 4[, STR_PAD_LEFT|STR_PAD_RIGHT|STR_PAD_BOTH $padType = STR_PAD_LEFT[, bool $perNewLine = false]]]) 

Adds indentation to given string. Optionally change the padding type. Optionally add padding per new line.

```php
$str = "Hello world!";

$indented = String::create($str)->addIdent(4);
echo $indented;
//Output
//    Hello World!

$multiLineStr = <<<TEST
Hello world
This is foo bar
TEST>>>;

$indented = String::create($multiLineStr)->addIdent(4, STR_PAD_LEFT, true);
echo $indented;
//Output:
//    Hello world
//    This is foo bar
```

#### getIndentSize
$string->getIndentSize();

Returns the current indentation size (number of spaces).

```php
$var = '    pie';
$indent = String::create($var)->getIndentSize();
echo $indent; //4
```
