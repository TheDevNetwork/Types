Documentation
=============
Please refer to the appropriate class to read it's API

- [StringType] + [Stringy](stringy-repo) + [Doctrine Inflector][doctrine-inflector]
- [BooleanType]
- [IntType]
- [FloatType]
- [DateTime] + [Carbon](carbon)
- [Collection] + [Doctrine Collections][doctrine-collections]

Requirements
------------

PHP 7.0 or above.

Optional Requirements:
* PHP BC Math (php-bcmath)
* PHP Gmp (php-gmp)


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

Type Credits
------------

<a href="https://github.com/doctrine">
  <img src="https://raw.githubusercontent.com/TheDevNetwork/Aux/master/images/doctrine-logo.png" alt="Doctrine Collections & Doctrine Inflector" width="160px" />
</a> 
<a href="https://github.com/briannesbitt/carbon">
  <img src="https://raw.githubusercontent.com/TheDevNetwork/Aux/master/images/carbon-logo.png" alt="Doctrine" width="160px" />
</a>
<a href="https://github.com/danielstjules/Stringy">
  <img src="https://raw.githubusercontent.com/TheDevNetwork/Aux/master/images/stringy.png" alt="Stringy" width="160px" />
</a>

[doctrine-inflector]: https://github.com/doctrine/inflector
[doctrine-collections]: https://github.com/doctrine/collections
[stringy-repo]: https://github.com/danielstjules/Stringy
