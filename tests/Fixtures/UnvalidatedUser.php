<?php

declare(strict_types=1);

namespace Enigma\Tests\Fixtures;

use Enigma\ValidatorTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * A model that uses the trait but declares no validation rules.
 */
class UnvalidatedUser extends Model
{
    use ValidatorTrait;

    protected $table = 'users';

    protected $guarded = [];
}
