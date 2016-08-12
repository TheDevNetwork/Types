Documentation
=============
Please refer to the appropriate class to read it's documentation

- [StringType] + [Stringy](stringy-repo) + [Doctrine Inflector][doctrine-inflector]
- [BooleanType]
- [IntType]
- [FloatType]
- [DateTime] + [Carbon](carbon)
- [Collection] + [Doctrine Collections][doctrine-collections]

Requirements
------------

PHP 7.0 or above.

Installation
------------

Using CLI:

```bash
php composer.phar require tdn/php-types:*@stable
```

In the `composer.json` file:
```json
{
    "require": {
        "tdn/php-types": "*@stable"
    }
}
```

API
---
In depth API documentation is located [here]().

Contributing
------------

If you want to contribute, please read the [CONTRIBUTING](../CONTRIBUTING.md).

License
-------

This library is released under the MIT license. See the complete license in the [LICENSE](LICENSE.md) file.

Credits
-------

* Boxing:
  * Based on: [instinct/types-autoboxing][autoboxing-repo]
* String:
  * Stringy Base: [danielstjules/Stringy][stringy-repo]
  * Doctrine Inflector: [doctrine/inflector][doctrine-inflector]
* DateTime:
  * Carbon Base: [briannesbitt/Carbon](https://github.com/briannesbitt/Carbon)

[BooleanType]: classes/boolean.md
[Collection]: classes/collection.md
[DateTime]: classes/datetime.md
[FloatType]: classes/float.md
[IntType]: classes/int.md
[StringType]: classes/string.md
[autoboxing-repo]: https://github.com/alquerci/php-types-autoboxing
[doctrine-inflector]: https://github.com/doctrine/inflector
[doctrine-collections]: https://github.com/doctrine/collections
[stringy-repo]: https://github.com/danielstjules/Stringy