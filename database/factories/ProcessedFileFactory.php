<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ProcessedFile;
use App\Enums\ProcessedFileStatusEnum;

class ProcessedFileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProcessedFile::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $processedFileStatus      = ProcessedFileStatusEnum::cases();
        $totalProcessedFileStatus = count($processedFileStatus);

        return [
            'original_filename' => Str::random(74),
            'stored_filename'   => Str::random(120),
            'status'            => $processedFileStatus[rand(0, $totalProcessedFileStatus)]->value,
        ];
    }
}
