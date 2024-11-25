<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Artikel>
 */
class ArtikelFactory extends Factory
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
            'category' => $this->faker->word(),
            'content' => $this->faker->text(),
            'date' => $this->faker->date(),
            'thumbnailURL' => $this->faker->imageUrl(),
            'title' => $this->faker->sentence(),
        ];
    }
}
