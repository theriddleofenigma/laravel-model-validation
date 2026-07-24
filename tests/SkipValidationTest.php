<?php

declare(strict_types=1);

namespace Enigma\Tests;

use Enigma\Tests\Fixtures\PropertyUser;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\Test;

class SkipValidationTest extends TestCase
{
    #[Test]
    public function skip_validation_bypasses_validation_on_save(): void
    {
        $user = (new PropertyUser(['name' => '', 'email' => 'not-an-email']))->skipValidation();

        $user->save();

        $this->assertTrue($user->exists);
        $this->assertDatabaseHas('users', ['email' => 'not-an-email']);
    }

    #[Test]
    public function skip_validation_can_be_re_enabled(): void
    {
        $user = (new PropertyUser(['name' => '', 'email' => 'not-an-email']))
            ->skipValidation()
            ->skipValidation(false);

        $this->expectException(ValidationException::class);

        $user->save();
    }

    #[Test]
    public function save_without_validation_persists_invalid_data_and_restores_the_flag(): void
    {
        $user = new PropertyUser(['name' => '', 'email' => 'not-an-email']);

        $this->assertTrue($user->saveWithoutValidation());
        $this->assertDatabaseCount('users', 1);

        // The skip only applied to that one save, so the next one validates.
        $this->assertFalse($user->shouldSkipValidation());

        $user->email = 'still-not-an-email';
        $this->expectException(ValidationException::class);
        $user->save();
    }

    #[Test]
    public function without_validation_disables_validation_for_the_callback(): void
    {
        $user = PropertyUser::withoutValidation(
            fn () => PropertyUser::create(['name' => '', 'email' => 'not-an-email'])
        );

        $this->assertTrue($user->exists);
        $this->assertDatabaseHas('users', ['email' => 'not-an-email']);
    }

    #[Test]
    public function without_validation_re_enables_validation_afterwards(): void
    {
        PropertyUser::withoutValidation(
            fn () => PropertyUser::create(['name' => '', 'email' => 'not-an-email'])
        );

        // Validation is active again outside the callback.
        $this->expectException(ValidationException::class);

        PropertyUser::create(['name' => '', 'email' => 'also-not-an-email']);
    }

    #[Test]
    public function without_validation_restores_the_flag_even_when_the_callback_throws(): void
    {
        try {
            PropertyUser::withoutValidation(function (): void {
                throw new \RuntimeException('boom');
            });
            $this->fail('Expected the callback exception to bubble up.');
        } catch (\RuntimeException $e) {
            $this->assertSame('boom', $e->getMessage());
        }

        $user = new PropertyUser(['name' => '', 'email' => 'not-an-email']);
        $this->assertFalse($user->shouldSkipValidation());
    }
}
