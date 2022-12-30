<?php

namespace Database\Factories;

use App\Enums\CartEnum;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class CartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'cart_number' => $this->faker->creditCardNumber,
            'cart_cvv2' => $this->faker->numberBetween(0,100),
            'image' => $this->faker->imageUrl,
            'expire' => '23/07',
            'category_id' => Category::factory()->create()->id,
            'status' => array_rand(CartEnum::getStatus())
        ];
    }
}
