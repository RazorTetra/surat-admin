<?php
// database/factories/LetterTrackingFactory.php

namespace Database\Factories;

use App\Models\User;
use App\Models\LetterTracking;
use Illuminate\Database\Eloquent\Factories\Factory;

class LetterTrackingFactory extends Factory
{
    protected $model = LetterTracking::class;

    public function definition(): array
    {
        return [
            'status' => $this->faker->randomElement(['created', 'processing', 'completed']),
            'description' => $this->faker->sentence(),
            'metadata' => [
                'location' => $this->faker->department,
                'action' => $this->faker->randomElement(['received', 'reviewed', 'approved', 'forwarded']),
                'notes' => $this->faker->optional()->sentence()
            ],
            'user_id' => User::factory(),
        ];
    }

    public function received(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'received',
            'metadata' => [
                'location' => 'Front Office',
                'action' => 'received',
                'received_by' => $this->faker->name()
            ]
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'metadata' => [
                'location' => 'Archives',
                'action' => 'archived',
                'completed_at' => now()->toDateTimeString()
            ]
        ]);
    }
}