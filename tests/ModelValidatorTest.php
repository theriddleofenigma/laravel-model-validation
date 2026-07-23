<?php

declare(strict_types=1);

namespace Enigma\Tests;

use Enigma\ModelValidator;
use Enigma\Tests\Fixtures\MethodUser;
use Enigma\Tests\Fixtures\PrecedenceUser;
use Enigma\Tests\Fixtures\PropertyUser;
use Enigma\Tests\Fixtures\TransformingUser;
use Enigma\Tests\Fixtures\UnvalidatedUser;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\Test;

class ModelValidatorTest extends TestCase
{
    #[Test]
    public function it_returns_the_validated_data_when_the_model_is_valid(): void
    {
        $user = new PropertyUser(['name' => 'Kumar', 'email' => 'kumar@example.com']);

        $validated = (new ModelValidator($user))->validate();

        $this->assertSame(['name' => 'Kumar', 'email' => 'kumar@example.com'], $validated);
    }

    #[Test]
    public function it_throws_a_validation_exception_when_the_model_is_invalid(): void
    {
        $user = new PropertyUser(['name' => '', 'email' => 'not-an-email']);

        try {
            (new ModelValidator($user))->validate();
            $this->fail('Expected a ValidationException to be thrown.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('name', $e->errors());
            $this->assertArrayHasKey('email', $e->errors());
        }
    }

    #[Test]
    public function it_skips_validation_when_no_rules_are_declared(): void
    {
        $user = new UnvalidatedUser(['name' => '']);

        $this->assertSame([], (new ModelValidator($user))->validate());
    }

    #[Test]
    public function it_applies_custom_validation_messages(): void
    {
        $user = new PropertyUser(['name' => '', 'email' => 'kumar@example.com']);

        try {
            (new ModelValidator($user))->validate();
            $this->fail('Expected a ValidationException to be thrown.');
        } catch (ValidationException $e) {
            $this->assertSame('The name is a must.', $e->errors()['name'][0]);
        }
    }

    #[Test]
    public function it_applies_custom_attribute_names(): void
    {
        $user = new PropertyUser(['name' => 'this name is far too long', 'email' => 'kumar@example.com']);

        try {
            (new ModelValidator($user))->validate();
            $this->fail('Expected a ValidationException to be thrown.');
        } catch (ValidationException $e) {
            $this->assertStringContainsString('User Name', $e->errors()['name'][0]);
        }
    }

    #[Test]
    public function it_resolves_configuration_declared_through_methods(): void
    {
        $user = new MethodUser(['name' => '', 'email' => 'kumar@example.com']);

        try {
            (new ModelValidator($user))->validate();
            $this->fail('Expected a ValidationException to be thrown.');
        } catch (ValidationException $e) {
            $this->assertSame('The name is a must.', $e->errors()['name'][0]);
        }
    }

    #[Test]
    public function a_method_takes_precedence_over_a_property_of_the_same_name(): void
    {
        // The property rules only require `name`, the method rules require a
        // valid `email`; the method must win.
        $user = new PrecedenceUser(['name' => 'Kumar']);

        try {
            (new ModelValidator($user))->validate();
            $this->fail('Expected a ValidationException to be thrown.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('email', $e->errors());
            $this->assertArrayNotHasKey('name', $e->errors());
        }
    }

    #[Test]
    public function it_uses_the_transformed_validation_data(): void
    {
        // The stored name is uppercase but validationData() lowercases it, so
        // the `lowercase` rule passes and the persisted value is untouched.
        $user = new TransformingUser(['name' => 'KUMAR']);

        $validated = (new ModelValidator($user))->validate();

        $this->assertSame('kumar', $validated['name']);
        $this->assertSame('KUMAR', $user->name);
    }

    #[Test]
    public function it_can_build_the_underlying_validator_instance(): void
    {
        $user = new PropertyUser(['name' => 'Kumar', 'email' => 'kumar@example.com']);

        $validator = (new ModelValidator($user))->makeValidator();

        $this->assertInstanceOf(ValidatorContract::class, $validator);
        $this->assertTrue($validator->passes());
    }
}
