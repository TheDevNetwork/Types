String
======
Currently this class extends [danielstjules/Stringy](https://github.com/danielstjules/Stringy) and provides
 the following additional methods:

* [pluralize](#pluralize)
* [singularize](#singularize)
* [strpos](#strpos)
* [strrpos](#strrpos)
* [valueOf](#valueof)

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

Returns substring between first instance of fromSubStr to first instance of toSubStr or end of string if toSubStr is not
 set. Optionally excludes fromSubStr. Optionally exclude toSubStr. Optionally case sensitive.

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
$string->strpos(string $subStr[, int $offset = null[, bool $caseSensitive = false]])

Returns position of the first occurance of subStr null if not present. Optional start index. Optionally case sensitive.

```php
$sentence = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';

$position = String::create($sentence)->strpos('ipsum');
echo $position; //6
```


#### strrpos
$string->strrpos(string $subStr[, int $offset = null[, bool $caseSensitive = false]])

Returns position of the last occurrence of subStr null if not present.

```php
$sentence = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';

$position = String::create($sentence)->strrpos('ipsum');
echo $position; //6
```

#### valueOf
$newString = String::valueOf(false);
echo $newString //"false"

$newString = String::valueOf($myObj);
echo $newString //"foo" aka whatever __toString is

$newString = String::valueOf(1.895);
echo $newString //"1.895"

etc.
