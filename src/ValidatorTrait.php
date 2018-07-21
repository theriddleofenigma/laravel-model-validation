<?php

namespace App\Packages;

trait ValidatorTrait
{
    public function validate()
    {
        // Runs the logic that might be executed before the model validation
        if (method_exists($this, 'beforeValidation')) $this->beforeValidation();

        // Model validation
        (new ModelValidator($this))->validate();

        // Runs the logic that might be executed after the model validation
        if (method_exists($this, 'afterValidation')) $this->afterValidation();
    }
}