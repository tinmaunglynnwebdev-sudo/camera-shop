<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);
        $price = $this->faker->randomFloat(2, 500, 6000);
        $salePrice = $this->faker->boolean(30) ? $price * 0.8 : $price;

        return [
            'name' => Str::title($name),
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(),
            'price' => $price,
            'original_price' => $price * 1.1,
            'sale_price' => $salePrice,
            'stock' => $this->faker->numberBetween(0, 50),
            'image' => 'https://placehold.co/600x400?text=' . urlencode($name),
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'brand_id' => Brand::inRandomOrder()->first()?->id ?? Brand::factory(),
            'views_count' => $this->faker->numberBetween(0, 10000),
            'specifications' => [
                'quality' => $this->faker->randomElement(['4K 60p', '8K 30p', 'Full HD']),
                'engine' => $this->faker->word(),
                'sensor' => $this->faker->randomElement(['Full-Frame', 'APS-C', 'Micro Four Thirds']),
                'iso' => '100-' . $this->faker->randomElement(['25600', '51200', '102400']),
                'battery' => $this->faker->word()
            ]
        ];
    }
}
