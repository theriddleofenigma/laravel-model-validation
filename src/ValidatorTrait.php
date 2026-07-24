<?php

declare(strict_types=1);

namespace Enigma;

use Closure;
use Illuminate\Validation\ValidationException;

/**
 * Adds self-contained validation to an Eloquent model.
 *
 * The model declares its own rules, messages and attributes (see
 * {@see ModelValidator}) and may optionally implement `beforeValidation()` and
 * `afterValidation()` hooks that run around each validation pass.
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait ValidatorTrait
{
    /**
     * Whether validation is skipped for this model instance.
     */
    protected bool $validationSkipped = false;

    /**
     * Whether validation is disabled for every instance of this model.
     */
    protected static bool $validationDisabled = false;

    /**
     * Validate the model, running the optional before/after hooks.
     *
     * When validation is skipped for this instance (or disabled for the model)
     * nothing is validated and an empty array is returned.
     *
     * @return array<string, mixed> The validated data.
     *
     * @throws ValidationException
     */
    public function validate(): array
    {
        if ($this->shouldSkipValidation()) {
            return [];
        }

        if (method_exists($this, 'beforeValidation')) {
            $this->beforeValidation();
        }

        $validated = $this->modelValidator()->validate();

        if (method_exists($this, 'afterValidation')) {
            $this->afterValidation();
        }

        return $validated;
    }

    /**
     * Determine whether validation should be skipped for this model.
     */
    public function shouldSkipValidation(): bool
    {
        return $this->validationSkipped || static::$validationDisabled;
    }

    /**
     * Skip (or re-enable) validation for this model instance.
     *
     * The flag stays in effect until it is changed, so subsequent saves on the
     * same instance are affected too.
     */
    public function skipValidation(bool $skip = true): static
    {
        $this->validationSkipped = $skip;

        return $this;
    }

    /**
     * Save the model to the database without validating it.
     *
     * Only this save is skipped; the instance's previous skip state is restored
     * afterwards.
     *
     * @param  array<string, mixed>  $options
     */
    public function saveWithoutValidation(array $options = []): bool
    {
        $previous = $this->validationSkipped;
        $this->validationSkipped = true;

        try {
            return $this->save($options);
        } finally {
            $this->validationSkipped = $previous;
        }
    }

    /**
     * Run the given callback with validation disabled for this model.
     *
     * Useful for operations that create or update models through the query
     * builder, e.g. `User::withoutValidation(fn () => User::create([...]))`.
     *
     * @template TReturn
     *
     * @param  Closure(): TReturn  $callback
     * @return TReturn
     */
    public static function withoutValidation(Closure $callback): mixed
    {
        $previous = static::$validationDisabled;
        static::$validationDisabled = true;

        try {
            return $callback();
        } finally {
            static::$validationDisabled = $previous;
        }
    }

    /**
     * Get a model validator instance for this model.
     */
    public function modelValidator(): ModelValidator
    {
        return new ModelValidator($this);
    }

    /**
     * Register a saving event listener that validates the model.
     */
    public static function validateOnSaving(): void
    {
        static::saving(static fn ($model) => $model->validate());
    }

    /**
     * Register a creating event listener that validates the model.
     */
    public static function validateOnCreating(): void
    {
        static::creating(static fn ($model) => $model->validate());
    }

    /**
     * Register an updating event listener that validates the model.
     */
    public static function validateOnUpdating(): void
    {
        static::updating(static fn ($model) => $model->validate());
    }
}
