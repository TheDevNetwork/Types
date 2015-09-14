BooleanType
=======
Class provides the following methods.

* [valueOf](#valueof)

#### valueOf
BooleanType::valueOf($mixed)

Returns the boolean (if applicable) of the evaluated variable.

```php
$boolean = BooleanType::valueOf(false);
var_dump($boolean->getValue()) //false

$boolean = BooleanType::valueOf(new \StdClass());
var_dump($boolean->getValue()) //true

$boolean = BooleanType::valueOf("true");
var_dump($boolean->getValue()) //true
```
