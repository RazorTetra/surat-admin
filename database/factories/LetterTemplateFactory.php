<?php
// database/factories/LetterTemplateFactory.php

namespace Database\Factories;

use App\Models\User;
use App\Models\LetterTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

class LetterTemplateFactory extends Factory
{
    protected $model = LetterTemplate::class;

    public function definition(): array
    {
        $templates = [
            'Surat Undangan Rapat',
            'Surat Tugas',
            'Surat Keterangan',
            'Surat Rekomendasi',
            'Surat Permohonan'
        ];

        $name = $this->faker->unique()->randomElement($templates);
        
        return [
            'name' => $name,
            'code' => strtoupper(str_replace(' ', '_', $name)),
            'content' => $this->faker->text(1000),
            'variables' => [
                'nama' => 'Nama Lengkap',
                'tanggal' => 'Tanggal Surat',
                'tempat' => 'Tempat Pelaksanaan',
                'waktu' => 'Waktu Pelaksanaan'
            ],
            'description' => $this->faker->sentence(),
            'type' => $this->faker->randomElement(['incoming', 'outgoing']),
            'is_active' => true,
            'created_by' => User::factory(),
            'updated_by' => $this->faker->optional()->randomElement([User::factory()]),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false
        ]);
    }
}