<?php

namespace Database\Factories;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $verified = $this->faker->randomElement([User::VERIFIED_USER, User::UNVERIFIED_USER]);
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'verified' => $verified,
            'verification_token' => $verified === User::VERIFIED_USER ? null : User::generateVerificationCode(),
            'admin' => $verified === User::VERIFIED_USER ? $this->faker->randomElement([User::ADMIN_USER, User::REGULAR_USER]) : User::REGULAR_USER,

            
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
