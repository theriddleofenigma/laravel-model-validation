<?php

namespace Enigma;

trait ValidatorTrait
{
    /**
     * Calls the validator instance to validate
     * Also runs the beforeValidation and afterValidation methods if exists.
     */
    public function validate()
    {
        // Runs the logic that might be executed before the model validation
        if (method_exists($this, 'beforeValidation')) {
            $this->beforeValidation();
        }

        // Model validation
        (new ModelValidator($this))->validate();

        // Runs the logic that might be executed after the model validation
        if (method_exists($this, 'afterValidation')) {
            $this->afterValidation();
        }
    }

    /**
     * Alias method for calling the validate method on saving the model.
     */
    public static function validateOnSaving()
    {
        static::saving(function ($model) {
            $model->validate();
        });
    }

    /**
     * Alias method for calling the validate method on creating the model.
     */
    public static function validateOnCreating()
    {
        static::creating(function ($model) {
            $model->validate();
        });
    }
}
