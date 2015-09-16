StringType
======
Currently this class extends [danielstjules/Stringy](https://github.com/danielstjules/Stringy) and provides
 the following additional methods:

* [pluralize](#pluralize)
* [singularize](#singularize)
* [strpos](#strpos)
* [strrpos](#strrpos)
* [from](#from)

#### pluralize
$string->pluralize()

Returns the word in plural form.

```php
$word = StringType::create('syllabus')->pluralize();
echo $word; //syllabi
```

#### singularize
$string->singularize()

Returns the word in singular form.

```php
$word = StringType::create('walruses')->singularize();
echo $word; //walrus
```

#### strpos
$string->strpos(string $subStr[, int $offset = null[, bool $caseSensitive = false]])

Returns position of the first occurance of subStr null if not present. Optional start index. Optionally case sensitive.

```php
$sentence = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';

$position = StringType::create($sentence)->strpos('ipsum');
echo $position; //6
```


#### strrpos
$string->strrpos(string $subStr[, int $offset = null[, bool $caseSensitive = false]])

Returns position of the last occurrence of subStr null if not present.

```php
$sentence = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';

$position = StringType::create($sentence)->strrpos('ipsum');
echo $position; //6
```

#### from
StringType::from($mixed)

Returns the evaluated value of $mixed as a string.

```php
$newString = StringType::from($myObj);
echo $newString //"foo" aka whatever __toString is

$newString = StringType::from(1.895);
echo $newString //"1.895"
```php

See tests for other usages.
