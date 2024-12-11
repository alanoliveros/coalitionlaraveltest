<?php

namespace Database\Factories;

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductFactory
{
    protected $count;

    public function definition(Faker $faker)
    {
        // predefined list of product names
        $productNames = ['Laptop', 'Smartphone', 'Tablet', 'Headphones', 'Camera', 'Smartwatch', 'Monitor', 'Keyboard', 'Mouse', 'Charger'];

        $productName = $faker->randomElement($productNames);

        // generate quantities and prices
        $quantity = $faker->numberBetween(1, 100);
        $price = $faker->numberBetween(100, 2000);

        return [
            'product_name' => $productName,
            'quantity' => $quantity,
            'price' => $price,
            'total_value' => $quantity * $price,
            'submitted_at' => now()->toDateTimeString(),
        ];
    }

    public static function new()
    {
        return new static();
    }

    public function count($count)
    {
        $this->count = $count;
        return $this;
    }

    public function make()
    {
        $products = [];
        for ($i = 0; $i < $this->count; $i++) {
            $products[] = $this->definition(app(Faker::class));
        }
        return collect($products);
    }
}

