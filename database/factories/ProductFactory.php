<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Product;

class ProductFactory extends Factory
{
    use HasFactory;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name'          => $this->faker->name(),
            'free_shipping' => $this->faker->boolean(),
            'description'   => $this->faker->sentence(),
            'price'         => $this->faker->randomFloat(2, 1, 100),
        ];
    }
}
