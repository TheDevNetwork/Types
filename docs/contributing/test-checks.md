Summary
-------
The library comes with a unit test suite and a coding standard.

Note:

It's recommended you just run the following 2 commands and save yourself some trouble:

```bash
$ vendor/bin/robo autofix
$ vendor/bin/robo test
```

Will auto-fix most code style issues and run the test entire test suite.

PHPUnit
-------
All the configuration for PHPUnit tests are located in the `.phpunit.xml.dist` file.

To manually run (from the project root):
```bash
$ vendor/bin/phpunit -c .
```

PHPCS
-----
All of the configurations for PHP Code Sniffer are located in the `.phpcs.xml` file.

To manually run (from the project root):
```bash
$ vendor/bin/phpcs --standard=.phpcs.xml
```

PHP Lint
--------
You can run parallel lint
```bash
$ vendor/bin/parallel-lint --exclude vendor --exclude build .
```

PHPCSFixer
----------
Optionally, run
```bash
$ vendor/bin/php-cs-fixer fix /path/to/dir --level=symfony --fixers=-concat_without_spaces
```

To ensure that all files will meet the standards.
