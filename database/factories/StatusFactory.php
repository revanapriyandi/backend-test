<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class StatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hash' => Str::random('32'),
            'body' => $this->faker->sentence(),
            'user_id' => rand(1, 85),
        ];
    }
}
