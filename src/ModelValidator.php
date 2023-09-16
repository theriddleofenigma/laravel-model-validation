<?php

namespace Enigma;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

/**
 * Class ModelValidator.
 */
class ModelValidator
{
    /**
     * The model object to validate.
     *
     * @var
     */
    protected Model $model;

    /**
     * The data to be validated.
     *
     * @var array
     */
    protected array $data;

    /**
     * The rules to be applied to the data.
     *
     * @var array
     */
    protected array $rules;

    /**
     * The array of custom error messages.
     *
     * @var array
     */
    protected array $customMessages;

    /**
     * The array of custom attribute names.
     *
     * @var array
     */
    protected array $customAttributes;

    /**
     * ModelValidator constructor.
     *
     * @param $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->initialize();
    }

    /**
     * Initialize this class by setting up the needed params.
     *
     * @return $this
     */
    public function initialize()
    {
        $this->customMessages = $this->getMessages();
        $this->customAttributes = $this->getAttributes();
        $this->rules = $this->getRules();
        $this->data = $this->getData();

        return $this;
    }

    /**
     * Validate the model params.
     *
     * @return $this
     */
    public function validate()
    {
        if ($this->rules) {
            Validator::make($this->data, $this->rules)
                ->setCustomMessages($this->customMessages)
                ->addCustomAttributes($this->customAttributes)
                ->validate();
        }

        return $this;
    }

    /**
     * Get the validation messages.
     *
     * @return array
     */
    protected function getMessages()
    {
        if (method_exists($this->model, 'validationMessages')) {
            return $this->model->validationMessages();
        }

        if (property_exists($this->model, 'validationMessages')) {
            return $this->model->validationMessages;
        }

        return [];
    }

    /**
     * Get the validation attributes.
     *
     * @return array
     */
    protected function getAttributes()
    {
        if (method_exists($this->model, 'validationAttributes')) {
            return $this->model->validationAttributes();
        }

        if (property_exists($this->model, 'validationAttributes')) {
            return $this->model->validationAttributes;
        }

        return [];
    }

    /**
     * Get the validation rules.
     *
     * @return array
     */
    protected function getRules()
    {
        if (method_exists($this->model, 'validationRules')) {
            return $this->model->validationRules();
        }

        if (property_exists($this->model, 'validationRules')) {
            return $this->model->validationRules;
        }

        return [];
    }

    /**
     * Get the validation data.
     *
     * @return array
     */
    protected function getData()
    {
        $data = $this->model->getAttributes();
        if (method_exists($this->model, 'validationData')) {
            return $this->model->validationData($data);
        }

        return $data;
    }
}
