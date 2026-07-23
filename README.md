# Laravel Model Validation

[![Tests](https://github.com/theriddleofenigma/laravel-model-validation/actions/workflows/tests.yml/badge.svg)](https://github.com/theriddleofenigma/laravel-model-validation/actions/workflows/tests.yml)
[![Latest Stable Version](https://img.shields.io/packagist/v/theriddleofenigma/laravel-model-validation.svg)](https://packagist.org/packages/theriddleofenigma/laravel-model-validation)
[![Total Downloads](https://img.shields.io/packagist/dt/theriddleofenigma/laravel-model-validation.svg)](https://packagist.org/packages/theriddleofenigma/laravel-model-validation)
[![License](https://img.shields.io/packagist/l/theriddleofenigma/laravel-model-validation.svg)](https://packagist.org/packages/theriddleofenigma/laravel-model-validation)

Effortless, self-contained validation for your Eloquent models.

Keep your validation rules where the data lives. Declare the rules on the model,
opt in to the model event you care about, and every save is validated
automatically &mdash; no form requests, no repeated calls to the validator.

## Requirements

| Package | Version              |
|---------|----------------------|
| PHP     | 8.2, 8.3, 8.4        |
| Laravel | 11.x, 12.x, 13.x     |

> Laravel 13 requires PHP 8.3 or newer.

## Installation

```shell
composer require theriddleofenigma/laravel-model-validation
```

## Quick start

Add the `Enigma\ValidatorTrait` to a model, declare its rules, and register the
event you want to validate on:

```php
use Enigma\ValidatorTrait;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use ValidatorTrait;

    public array $validationRules = [
        'name' => 'required|max:10',
        'email' => 'required|email',
    ];

    protected static function boot(): void
    {
        parent::boot();

        // Validate the model automatically whenever it is saved.
        static::validateOnSaving();
    }
}
```

Now any attempt to save an invalid model throws an
`Illuminate\Validation\ValidationException`, exactly like Laravel's own
validation &mdash; so in an HTTP context the errors are flashed and redirected
for you automatically.

```php
User::create(['name' => 'Kumar', 'email' => 'not-an-email']); // throws ValidationException
```

## Registering validation

Three helpers register the matching Eloquent event listener for you:

```php
static::validateOnSaving();   // fires on create and update
static::validateOnCreating(); // fires on create only
static::validateOnUpdating(); // fires on update only
```

Prefer to validate on a different event, or on demand? Call `validate()`
yourself. It returns the validated data and throws on failure:

```php
$validated = $user->validate();
```

## Customising the configuration

Rules, messages and attribute names can each be declared **either** as a
property **or** as a method of the same name. A method always takes precedence,
so you can compute the configuration dynamically when you need to.

```php
class User extends Model
{
    use ValidatorTrait;

    public array $validationMessages = [
        'name.required' => 'Name field is required.',
        'email.email' => 'The given email is in an invalid format.',
    ];

    public array $validationAttributes = [
        'name' => 'User Name',
    ];

    public function validationRules(): array
    {
        return [
            'name' => 'required|max:10',
            'email' => ['required', 'email', 'unique:users,email,' . $this->id],
        ];
    }
}
```

## Controlling the data that gets validated

By default the model's raw attributes are validated. Declare a
`validationData()` method to reshape that data first &mdash; the returned value
is used only for validation and never changes what is persisted.

```php
/**
 * @param  array<string, mixed>  $data  The value of $this->getAttributes().
 * @return array<string, mixed>
 */
public function validationData(array $data): array
{
    $data['name'] = strtolower($data['name']);

    return $data;
}
```

## Before & after hooks

Implement `beforeValidation()` and/or `afterValidation()` to run logic around
each validation pass:

```php
public function beforeValidation(): void
{
    // Normalise attributes, set defaults, etc.
}

public function afterValidation(): void
{
    // Anything that should run once validation succeeds.
}
```

## Testing

```shell
composer install
composer test
```

## Contributing

Pull requests are welcome. Please make sure the test suite passes and add
coverage for any behaviour you change.

## License

Laravel Model Validation is open-sourced software licensed under the
[MIT license](https://opensource.org/licenses/MIT).
