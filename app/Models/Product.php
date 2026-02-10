<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 
        'slug', 
        'category_id', 
        'brand_id', 
        'price', 
        'description', 
        'image', 
        'gallery',
        'specifications',
        'stock'
    ];

    protected $casts = [
        'gallery' => 'array',
        'specifications' => 'array',
        'price' => 'decimal:2'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
