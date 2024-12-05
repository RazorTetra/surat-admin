<?php
// database/factories/IncomingLetterFactory.php

namespace Database\Factories;

use App\Models\User;
use App\Models\IncomingLetter;
use Illuminate\Database\Eloquent\Factories\Factory;

class IncomingLetterFactory extends Factory
{
    protected $model = IncomingLetter::class;

    public function definition(): array
    {
        return [
            'reference_number' => 'IN/' . $this->faker->unique()->numerify('####') . '/' . date('Y'),
            'origin' => $this->faker->company(),
            'sender' => $this->faker->name(),
            'recipient' => $this->faker->randomElement(['Ketua Jurusan', 'Sekretaris Jurusan', 'Koordinator Prodi']),
            'subject' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'letter_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'received_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'type' => $this->faker->randomElement(['official', 'personal', 'confidential']),
            'priority' => $this->faker->randomElement(['high', 'medium', 'low']),
            'status' => $this->faker->randomElement(['received', 'processed', 'completed']),
            'notes' => $this->faker->optional()->sentence(),
            'received_by' => User::factory(),
            'processed_by' => $this->faker->optional()->randomElement([User::factory()]),
        ];
    }

    public function highPriority(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'high'
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'processed_by' => User::factory()
        ]);
    }
}