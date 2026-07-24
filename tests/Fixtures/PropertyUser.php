<?php

declare(strict_types=1);

namespace Enigma\Tests\Fixtures;

use Enigma\ValidatorTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * A model that declares its validation configuration through properties and
 * validates automatically on the saving event.
 */
class PropertyUser extends Model
{
    use ValidatorTrait;

    protected $table = 'users';

    protected $guarded = [];

    /** @var array<string, mixed> */
    public $validationRules = [
        'name' => 'required|max:10',
        'email' => 'required|email',
    ];

    /** @var array<string, string> */
    public $validationMessages = [
        'name.required' => 'The name is a must.',
        'email.email' => 'That is not a valid email.',
    ];

    /** @var array<string, string> */
    public $validationAttributes = [
        'name' => 'User Name',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::validateOnSaving();
    }
}
