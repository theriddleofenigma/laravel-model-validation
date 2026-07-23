<?php

declare(strict_types=1);

namespace Enigma\Tests\Fixtures;

use Enigma\ValidatorTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * A model that validates only on the creating event.
 */
class CreatingUser extends Model
{
    use ValidatorTrait;

    protected $table = 'users';

    protected $guarded = [];

    /** @var array<string, mixed> */
    public $validationRules = [
        'name' => 'required',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::validateOnCreating();
    }
}
