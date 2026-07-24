<?php

declare(strict_types=1);

namespace Enigma;

use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Resolves the validation configuration declared on an Eloquent model and
 * validates the model's attributes against it.
 *
 * Every piece of the configuration - rules, messages, attributes and the data
 * itself - may be declared on the model either as a public property or as a
 * public method of the same name. A method always takes precedence over a
 * property, allowing the configuration to be computed dynamically.
 */
class ModelValidator
{
    public function __construct(
        protected readonly Model $model,
    ) {
    }

    /**
     * Validate the model's data against its rules.
     *
     * When the model declares no rules, validation is skipped and an empty
     * array is returned.
     *
     * @return array<string, mixed> The validated data.
     *
     * @throws ValidationException
     */
    public function validate(): array
    {
        $rules = $this->rules();

        if ($rules === []) {
            return [];
        }

        return $this->makeValidator($rules)->validate();
    }

    /**
     * Build the underlying validator instance for the model.
     *
     * @param  array<string, mixed>|null  $rules
     */
    public function makeValidator(?array $rules = null): ValidatorContract
    {
        return Validator::make(
            $this->data(),
            $rules ?? $this->rules(),
            $this->messages(),
            $this->attributes(),
        );
    }

    /**
     * Get the validation rules declared on the model.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return $this->resolve('validationRules');
    }

    /**
     * Get the custom validation messages declared on the model.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return $this->resolve('validationMessages');
    }

    /**
     * Get the custom attribute names declared on the model.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return $this->resolve('validationAttributes');
    }

    /**
     * Get the data that should be validated.
     *
     * Defaults to the model's raw attributes, but the model may reshape it by
     * declaring a `validationData(array $data): array` method. The returned
     * data never affects the values persisted to the database.
     *
     * @return array<string, mixed>
     */
    public function data(): array
    {
        $data = $this->model->getAttributes();

        if (method_exists($this->model, 'validationData')) {
            return $this->model->validationData($data);
        }

        return $data;
    }

    /**
     * Resolve a validation config value from the model.
     *
     * A method of the given name is preferred over a property of the same
     * name; when neither is present an empty array is returned.
     *
     * @return array<mixed>
     */
    protected function resolve(string $name): array
    {
        if (method_exists($this->model, $name)) {
            return (array) $this->model->{$name}();
        }

        if (property_exists($this->model, $name)) {
            return (array) $this->model->{$name};
        }

        return [];
    }
}
