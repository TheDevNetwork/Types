Boolean
=======
Class provides the following methods.

* [valueOf](#valueof)

#### valueOf
Boolean::valueOf($mixed)

Returns the boolean (if applicable) of the evaluated variable.

```php
$boolean = Boolean::valueOf(false);
var_dump($boolean->getValue()) //false

$boolean = Boolean::valueOf(new \StdClass());
var_dump($boolean->getValue()) //true

$boolean = Boolean::valueOf("true");
var_dump($boolean->getValue()) //true
```
