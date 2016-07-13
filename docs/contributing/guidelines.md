Guidelines
----------
Thank you for taking the time to read this document. Pull requests are always welcomed and appreciated.

![+1 Internet][one free internet]

PhpTypes is an open source project that uses the [MIT](http://opensource.org/licenses/MIT) license.

Contributors should keep in mind the following rules when creating pull requests:

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119].

  * You SHOULD rebase against the `develop` branch (recommended frequently) and squash your commit when submitting.
    See the [apache git usage] document which explains the why and this [tutorial] which explains the how.
    Extra information about rebasing/reflog can be located in the [rebase documentation]
    and this [reflog tutorial] respectively.

  * You MUST follow [PSR-1] and [PSR-2].

  * You SHOULD [run the local checks].

  * You MUST write/update unit tests accordingly.

  * You MUST write a description which gives context to the PR.

  * You SHOULD write/update documentation accordingly.

  * The following checks will be automatically performed on PRs:
     - Code Style (PSR-1, PSR-2)
     - Scrutinizer checks
     - PhpUnit tests

Notes:

- If any of those fail the PR will not be merged until it is updated accordingly.

Thank you for any and all contributions or simply using the lib.

Run The Local Checks
--------------------

### All Checks (Recommended)
Run the following command to run all checks: `vendor/bin/robo test:all`

That will run:

* PHPUnit
* Parallel Lint
* PHPCS

### Specific
#### PHPUnit
All the configuration for PHPUnit tests are located in the `.phpunit.xml.dist` file.

To manually run (from the project root):
```bash
$ vendor/bin/phpunit -c .
```

#### PHPCS
All of the configurations for PHP Code Sniffer are located in the `.phpcs.xml` file.

To manually run (from the project root):
```bash
$ vendor/bin/phpcs --standard=.phpcs.xml
```

#### Lint Check
To manually run (from the project root):

```bash
$ vendor/bin/parallel-lint --exclude vendor --exclude build .
```


[one free internet]: https://raw.githubusercontent.com/TheDevNetwork/Aux/master/images/OneFreeInternet.png
[view the contributing docs online]: https://thedevnetwork.github.io/TdnPilotBundle/contributing/index.md
[run the local checks]: #run-the-local-checks
[apache git usage]: https://cwiki.apache.org/confluence/display/FLEX/Good+vs+Bad+Git+usage
[tutorial]: http://gitready.com/advanced/2009/02/10/squashing-commits-with-rebase.html
[reflog tutorial]: https://www.atlassian.com/git/tutorials/rewriting-history/git-reflog
[rebase documentation]: http://git-scm.com/book/en/v2/Git-Branching-Rebasing
[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
