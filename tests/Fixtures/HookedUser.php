<?php

declare(strict_types=1);

namespace Enigma\Tests\Fixtures;

use Enigma\ValidatorTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * A model that records whether the before/after validation hooks ran.
 */
class HookedUser extends Model
{
    use ValidatorTrait;

    protected $table = 'users';

    protected $guarded = [];

    public bool $beforeValidationCalled = false;

    public bool $afterValidationCalled = false;

    /** @var array<string, mixed> */
    public $validationRules = [
        'name' => 'required',
    ];

    public function beforeValidation(): void
    {
        $this->beforeValidationCalled = true;
    }

    public function afterValidation(): void
    {
        $this->afterValidationCalled = true;
    }
}
