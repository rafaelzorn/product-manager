<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
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
     * @param string $originalFileName
     * @param string $stubFilePath
     * @param string $storePath
     *
     * @return Factory
     */
    public function storedFile(string $originalFileName, string $stubFilePath, string $storePath): Factory
    {
        $stubFilePath   = file_get_contents(base_path($stubFilePath));
        $storedFilename = UploadedFile::fake()
                            ->create($originalFileName, $stubFilePath)
                            ->store($storePath);

        return $this->state(function (array $attributes) use($storedFilename) {
            return [
                'stored_filename' => $storedFilename,
            ];
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'original_filename' => Str::random(74),
            'stored_filename'   => Str::random(120),
            'status'            => $this->faker->randomElement(ProcessedFileStatusEnum::cases()),
        ];
    }
}
