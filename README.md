<p align="center"><code>&hearts; Made with &lt;love/&gt; And I love &lt;code/&gt;</code></p>

<p align="center">
<a href="https://app.fossa.com/projects/git%2Bgithub.com%2Ftheriddleofenigma%2Flaravel-model-validation?ref=badge_shield" alt="FOSSA Status"><img src="https://app.fossa.com/api/projects/git%2Bgithub.com%2Ftheriddleofenigma%2Flaravel-model-validation.svg?type=shield"/></a>
</p>

# Laravel Model Validation
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2Ftheriddleofenigma%2Flaravel-model-validation.svg?type=shield)](https://app.fossa.io/projects/git%2Bgithub.com%2Ftheriddleofenigma%2Flaravel-model-validation?ref=badge_shield)

Model validation - Validates the model data. *Only for laravel applications.

An easy validator option for your eloquent models. Also have flexibility for additional codes that might be executed on before and after validation.

### Composer install
```shell
composer require theriddleofenigma/laravel-model-validation
```

## Usage Examples
Here user model is mentioned as an example. You could use this in any model you want.

### User.php model
    use Enigma\ValidatorTrait;

    class User extends Model 
    {
        use ValidatorTrait;

        /**
         * Boot method.
         */
        public static function boot()
        {
            parent::boot();
            
            // Add this method for validating the current model on model saving event
            static::validateOnSaving();
        }

        /**
         * Validation rules to validate.
         * 
         * @return array
         */
        public function validationRules()
        {
            return [
                'name' => 'required|max:10',
                'email' => 'required|email',
            ];
        }
    
        /**
         * Custom messages to replace the validation messages.
         * 
         * @return array
         */
        public function validationMessages()
        {
            return [
                'name.required' => 'Name field is required.',
                'email.email' => 'The given email is in invalid format.',
            ];
        }
    
        /**
         * Custom attribute names to replace the validation attribute name.
         * 
         * @return array
         */
        public function validationAttributes()
        {
            return [
                'name' => 'User Name'
            ];
        }
        
        /**
         * Code to be executed before the validation goes here.
         */
        public function beforeValidation()
        {
            // Some code goes here..
        }
        
        /**
         * Code to be executed after the validation goes here.
         */
        public function afterValidation()
        {
            // Some code goes here..
        }
    }

### Other options
You could mention the validation only for creating itself or on any model event just add `$model->validate()`.

        /**
         * Boot method.
         */
        public static function boot()
        {
            parent::boot();
            
            // You can mention like this for validating the model on custom events as your wish
            static::creating(function($model){
                $model->validate();
            });
            
            // Or you may an alias like `static::validateOnCreating()`.
        }

Refer the available methods in the validationTrait.

## License

Laravel Model Validation is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2Ftheriddleofenigma%2Flaravel-model-validation.svg?type=large)](https://app.fossa.io/projects/git%2Bgithub.com%2Ftheriddleofenigma%2Flaravel-model-validation?ref=badge_large)