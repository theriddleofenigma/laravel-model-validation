<?php

declare(strict_types=1);

namespace Enigma\Tests;

use Enigma\Tests\Fixtures\CreatingUser;
use Enigma\Tests\Fixtures\HookedUser;
use Enigma\Tests\Fixtures\PropertyUser;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\Test;

class ValidatorTraitTest extends TestCase
{
    #[Test]
    public function it_runs_the_before_and_after_hooks_around_validation(): void
    {
        $user = new HookedUser(['name' => 'Kumar']);

        $user->validate();

        $this->assertTrue($user->beforeValidationCalled);
        $this->assertTrue($user->afterValidationCalled);
    }

    #[Test]
    public function it_validates_the_model_on_saving_and_persists_valid_data(): void
    {
        $user = PropertyUser::create(['name' => 'Kumar', 'email' => 'kumar@example.com']);

        $this->assertTrue($user->exists);
        $this->assertDatabaseHas('users', ['email' => 'kumar@example.com']);
    }

    #[Test]
    public function it_blocks_saving_when_the_model_is_invalid(): void
    {
        $user = new PropertyUser(['name' => '', 'email' => 'not-an-email']);

        $this->expectException(ValidationException::class);

        try {
            $user->save();
        } finally {
            $this->assertDatabaseCount('users', 0);
        }
    }

    #[Test]
    public function it_validates_only_on_creating_when_configured(): void
    {
        $this->expectException(ValidationException::class);

        CreatingUser::create(['name' => '']);
    }

    #[Test]
    public function it_does_not_revalidate_on_update_when_only_creating_is_registered(): void
    {
        $user = CreatingUser::create(['name' => 'Kumar']);

        // Blanking the name would fail creation validation, but updating does
        // not trigger it, so the save succeeds.
        $user->name = '';
        $user->save();

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => '']);
    }
}
