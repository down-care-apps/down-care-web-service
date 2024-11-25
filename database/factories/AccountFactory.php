<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //add faker
            'age' => $this->faker->randomNumber(2),
            'createAt' => $this->faker->dateTime(),
            'displayName' => $this->faker->name(),
            'email' => $this->faker->email(),
            'name' => $this->faker->name(),
            'phoneNumber' => $this->faker->phoneNumber(),
            'photoURL' => $this->faker->imageUrl(),
            'uid' => $this->faker->uuid(),
        ];
    }
}
