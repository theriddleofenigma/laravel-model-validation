<?php

namespace Enigma;

use Illuminate\Support\Facades\Validator;

/**
 * Class ModelValidator
 *
 * @package App\Packages
 */
class ModelValidator
{
    /**
     * The model object to validate
     *
     * @var
     */
    protected $model;

    /**
     * The rules to be applied to the data.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * The array of custom error messages.
     *
     * @var array
     */
    protected $customMessages = [];

    /**
     * The array of custom attribute names.
     *
     * @var array
     */
    protected $customAttributes = [];

    /**
     * ModelValidator constructor
     *
     * @param $model
     */
    public function __construct($model)
    {
        $this->model = $model;
        $this->initialize();
    }

    /**
     * Initialize this class by setting up the needed params
     *
     * @return $this
     */
    public function initialize()
    {
        if (method_exists($this->model, 'validationMessages')) {
            $this->customMessages = array_merge($this->customMessages, $this->model->validationMessages());
        }

        if (method_exists($this->model, 'validationAttributes')) {
            $this->customAttributes = array_merge($this->customAttributes, $this->model->validationAttributes());
        }

        if (method_exists($this->model, 'validationRules')) {
            $this->rules = array_merge($this->rules, $this->model->validationRules());
        }

        return $this;
    }

    /**
     * Validate the model params
     *
     * @return $this
     */
    public function validate()
    {
        if ($this->rules) {
            Validator::make($this->model->toArray(), $this->rules)
                ->setCustomMessages($this->customMessages)
                ->addCustomAttributes($this->customAttributes)
                ->validate();
        }

        return $this;
    }
}
