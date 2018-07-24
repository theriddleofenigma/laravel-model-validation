# Laravel Model Validator
Model validator validates the model data. *Only for laravel applications.

An easy validator option for you eloquent models. Also have flexibility for additional codes that might be executed on before and after validation.

### Composer install
```shell
composer require codedevpal/laravel-model-validation
```

## Usage Examples
Here user model is mentioned as an example. You could use this in any model you want.

### User.php model
    use CodeDevPal\ValidatorTrait;
    class User extends Model 
    {
        use ValidatorTrait;

        /**
         * Boot method
         */
        public static function boot()
        {
            parent::boot();
            static::validateOnSaving();
        }

        /**
         * Validation rules to validate
         * 
         * @return array
         */
        public function validationRules()
        {
            return [
                'name' => 'required|max:2',
                'email' => 'required|email',
            ];
        }
    
        /**
         * Custom messages to replace the validation messages
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
         * Custom attribute names to replace the validation attribute name
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
         * Code to be executed before the validation goes here
         */
        public function beforeValidation()
        {
            // Some code goes here..
        }
        
        /**
         * Code to be executed after the validation goes here
         */
        public function afterValidation()
        {
            // Some code goes here..
        }
    }
