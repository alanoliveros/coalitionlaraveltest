<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    // (GET /products)
    public function index()
    {
        $filePath = storage_path('data/products.json');
        $products = File::exists($filePath) ? json_decode(File::get($filePath), true) : [];
        return response()->json($products);
    }

    // (POST /products)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'productName' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $productData = [
            'product_name' => $request->input('productName'),
            'quantity' => $request->input('quantity'),
            'price' => $request->input('price'),
            'total_value' => $request->input('quantity') * $request->input('price'),
            'submitted_at' => now()->toDateTimeString(),
        ];

        $filePath = storage_path('data/products.json');
        $existingData = File::exists($filePath) ? json_decode(File::get($filePath), true) : [];
        $existingData[] = $productData;

        File::put($filePath, json_encode($existingData, JSON_PRETTY_PRINT));

        return response()->json(['message' => 'Product added successfully!']);
    }



    // (GET /products/{id})
    public function show($id)
    {
        $filePath = storage_path('data/products.json');
        $products = File::exists($filePath) ? json_decode(File::get($filePath), true) : [];

        if (!isset($products[$id])) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($products[$id]);
    }


}
