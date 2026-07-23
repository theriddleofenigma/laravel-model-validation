<?php

declare(strict_types=1);

namespace Enigma\Tests\Fixtures;

use Enigma\ValidatorTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * A model that declares its validation configuration through methods.
 */
class MethodUser extends Model
{
    use ValidatorTrait;

    protected $table = 'users';

    protected $guarded = [];

    /** @return array<string, mixed> */
    public function validationRules(): array
    {
        return [
            'name' => 'required|max:10',
            'email' => 'required|email',
        ];
    }

    /** @return array<string, string> */
    public function validationMessages(): array
    {
        return [
            'name.required' => 'The name is a must.',
        ];
    }

    /** @return array<string, string> */
    public function validationAttributes(): array
    {
        return [
            'name' => 'User Name',
        ];
    }
}
