# Contributing

Thanks for taking the time to contribute! Contributions of all kinds are
welcome &mdash; bug reports, feature requests, documentation, and code.

## Code of Conduct

This project adheres to a [Code of Conduct](CODE_OF_CONDUCT.md). By
participating, you are expected to uphold it. Please report unacceptable
behaviour to the maintainer.

## Reporting bugs & requesting features

- Search the [existing issues](https://github.com/theriddleofenigma/laravel-model-validation/issues)
  first to avoid duplicates.
- Open a new issue using the appropriate template and include as much detail as
  you can (a minimal reproduction goes a long way).
- For usage questions, please use
  [Discussions](https://github.com/theriddleofenigma/laravel-model-validation/discussions)
  rather than the issue tracker.

## Development setup

This package targets **PHP 8.2+** and **Laravel 12 / 13**.

```shell
git clone https://github.com/theriddleofenigma/laravel-model-validation.git
cd laravel-model-validation
composer install
```

## Running the tests

The suite uses [PHPUnit](https://phpunit.de/) and
[Orchestra Testbench](https://github.com/orchestral/testbench):

```shell
composer test
```

Please make sure the whole suite passes and add coverage for any behaviour you
change or add.

## Pull request guidelines

1. Fork the repository and create your branch from `master`.
2. Keep each pull request focused on a single concern.
3. Follow the existing code style (PSR-12, `declare(strict_types=1)`, typed
   signatures).
4. Add or update tests for your change.
5. Update the documentation (`README.md`) and the `CHANGELOG.md` "Unreleased"
   section where relevant.
6. Ensure CI is green.

## Reporting security issues

Please do **not** open a public issue for security vulnerabilities. See
[SECURITY.md](SECURITY.md) for how to report them privately.
