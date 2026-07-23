<?php

declare(strict_types=1);

namespace Enigma\Tests\Fixtures;

use Enigma\ValidatorTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * A model that declares both a property and a method for its rules so the
 * resolution precedence (method wins) can be asserted.
 */
class PrecedenceUser extends Model
{
    use ValidatorTrait;

    protected $table = 'users';

    protected $guarded = [];

    /** @var array<string, mixed> */
    public $validationRules = [
        'name' => 'required',
    ];

    /** @return array<string, mixed> */
    public function validationRules(): array
    {
        return [
            'email' => 'required|email',
        ];
    }
}
