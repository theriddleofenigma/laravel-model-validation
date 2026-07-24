<?php

declare(strict_types=1);

namespace Enigma\Tests\Fixtures;

use Enigma\ValidatorTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * A model that reshapes the data before it is validated without affecting the
 * values that are persisted.
 */
class TransformingUser extends Model
{
    use ValidatorTrait;

    protected $table = 'users';

    protected $guarded = [];

    /** @var array<string, mixed> */
    public $validationRules = [
        'name' => 'required|lowercase',
    ];

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function validationData(array $data): array
    {
        $data['name'] = strtolower((string) ($data['name'] ?? ''));

        return $data;
    }
}
