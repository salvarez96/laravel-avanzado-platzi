<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorizedProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all products and categories
        $products = Product::all();
        $categories = Category::all();

        // Seed the pivot table
        foreach ($products as $product) {
            // Choose random number of categories (between 1 and 3)
            $numCategories = rand(1, 3);
            // Shuffle the categories to get random ones
            $randomCategories = $categories->shuffle()->take($numCategories);
            // Attach the random categories to the product
            foreach ($randomCategories as $category) {
                DB::table('categorized_products')->insert([
                    'product_id' => $product->id,
                    'category_id' => $category->id,
                    // Add any additional columns if needed
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
