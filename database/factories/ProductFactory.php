<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'=>$this->faker->name,
            'description'=>$this->faker->paragraph(1),
            'quantity'=>$this->faker->numberBetween(1,10),
            'status' => $this->faker->randomElement([Product::UNAVAILABLE_PRODUCT, Product::AVAILABLE_PRODUCT]),
            'image' => $this->faker->randomElement(['1.jpg', '2.jpg', '3.jpg']),
            'seller_id' => User::where('verified', User::VERIFIED_USER)->get()->random()->id,


        ];
    }
}
