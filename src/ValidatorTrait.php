<?php

declare(strict_types=1);

namespace Enigma;

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
     * Validate the model, running the optional before/after hooks.
     *
     * @return array<string, mixed> The validated data.
     *
     * @throws ValidationException
     */
    public function validate(): array
    {
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
