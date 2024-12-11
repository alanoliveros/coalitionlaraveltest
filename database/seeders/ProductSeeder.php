<?php

namespace Database\Seeders;

use Database\Factories\ProductFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // generate 10 sample
        $products = ProductFactory::new()->count(10)->make()->toArray();

        // file path
        $filePath = storage_path('data/products.json');

        // if the file exists, if so, append the new products
        if (File::exists($filePath)) {
            $existingData = json_decode(File::get($filePath), true) ?? [];
            $existingData = array_merge($existingData, $products);
        } else {
            // file doesn't exist, create it with the new products
            $existingData = $products;
        }

        // write the products data to the file
        File::put($filePath, json_encode($existingData, JSON_PRETTY_PRINT));
    }
}
