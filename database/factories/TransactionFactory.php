<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Seller;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $seller = Seller::has('products')->with('products')->get()->random();
        $buyer = User::where('verified', User::VERIFIED_USER)->get()->except($seller->id)->random();
        return [
            'quantity' => $this->faker->numberBetween(1, 5),
            'product_id' => $seller->products->random()->id,
            'buyer_id' => $buyer->id,
        ];

    }
}
