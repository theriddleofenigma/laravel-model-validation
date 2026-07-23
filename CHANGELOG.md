# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Support for Laravel 13.
- `validateOnUpdating()` helper to validate on the `updating` model event.
- `modelValidator()` accessor and `ModelValidator::makeValidator()` for building
  the underlying validator instance.
- Test suite (PHPUnit + Orchestra Testbench) and a GitHub Actions matrix
  covering PHP 8.2&ndash;8.4 and Laravel 12&ndash;13.
- Community health files: `CONTRIBUTING.md`, `SECURITY.md`, a pull request
  template, and refreshed issue templates.

### Changed
- **BREAKING:** Minimum requirements are now PHP 8.2 and Laravel 12.
- Depend on the individual `illuminate/*` components instead of the full
  `laravel/framework` package.
- Rewrote `ModelValidator` and `ValidatorTrait` with strict types, constructor
  property promotion, and typed signatures. `validate()` now returns the
  validated data.

### Removed
- Support for Laravel 11 and earlier (end-of-life).
