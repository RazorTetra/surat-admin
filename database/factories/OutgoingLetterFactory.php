<?php
// database/factories/OutgoingLetterFactory.php

namespace Database\Factories;

use App\Models\User;
use App\Models\OutgoingLetter;
use Illuminate\Database\Eloquent\Factories\Factory;

class OutgoingLetterFactory extends Factory
{
    protected $model = OutgoingLetter::class;

    public function definition(): array
    {
        return [
            'reference_number' => 'OUT/' . $this->faker->unique()->numerify('####') . '/' . date('Y'),
            'recipient' => $this->faker->company(),
            'recipient_address' => $this->faker->address(),
            'subject' => $this->faker->sentence(),
            'content' => $this->faker->paragraphs(3, true),
            'letter_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'type' => $this->faker->randomElement(['official', 'personal', 'confidential']),
            'priority' => $this->faker->randomElement(['high', 'medium', 'low']),
            'status' => $this->faker->randomElement(['draft', 'review', 'approved', 'sent']),
            'notes' => $this->faker->optional()->sentence(),
            'created_by' => User::factory(),
            'approved_by' => $this->faker->optional()->randomElement([User::factory()]),
            'sent_at' => $this->faker->optional()->dateTimeBetween('-1 month', 'now'),
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'approved_by' => User::factory()
        ]);
    }

    public function sent(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'sent',
            'sent_at' => now(),
            'approved_by' => User::factory()
        ]);
    }
}