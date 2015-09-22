BooleanType
===========
Class provides the following methods.

* [from](#from)

#### from
BooleanType::valueOf($mixed)

Returns the boolean (if applicable) of the evaluated variable.

```php
$boolean = BooleanType::from(false);
var_dump($boolean->getValue()) //false

$boolean = BooleanType::from(new \StdClass());
var_dump($boolean->getValue()) //true

$boolean = BooleanType::from("true");
var_dump($boolean->getValue()) //true
```

See tests for other uses.
