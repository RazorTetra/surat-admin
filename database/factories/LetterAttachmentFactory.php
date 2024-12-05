<?php
// database/factories/LetterAttachmentFactory.php

namespace Database\Factories;

use App\Models\User;
use App\Models\LetterAttachment;
use Illuminate\Database\Eloquent\Factories\Factory;

class LetterAttachmentFactory extends Factory
{
    protected $model = LetterAttachment::class;

    public function definition(): array
    {
        $filename = $this->faker->md5() . '.pdf';
        
        return [
            'filename' => $filename,
            'original_filename' => 'Lampiran_' . $this->faker->word() . '.pdf',
            'file_path' => 'attachments/' . $filename,
            'mime_type' => 'application/pdf',
            'file_size' => $this->faker->numberBetween(100000, 5000000),
            'description' => $this->faker->optional()->sentence(),
            'uploaded_by' => User::factory(),
        ];
    }

    public function image(): static
    {
        return $this->state(function (array $attributes) {
            $filename = $this->faker->md5() . '.jpg';
            return [
                'filename' => $filename,
                'original_filename' => 'Image_' . $this->faker->word() . '.jpg',
                'file_path' => 'attachments/' . $filename,
                'mime_type' => 'image/jpeg',
                'file_size' => $this->faker->numberBetween(50000, 2000000),
            ];
        });
    }
}