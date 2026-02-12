<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('brand', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('category')) {
            $categories = explode(',', $request->category);
            $query->whereHas('category', function ($q) use ($categories) {
                $q->whereIn('slug', $categories);
            });
        }

        if ($request->has('brand')) {
            $brands = explode(',', $request->brand);
            $query->whereHas('brand', function ($q) use ($brands) {
                $q->whereIn('slug', $brands);
            });
        }

        return response()->json($query->paginate(15));
    }

    public function show($slug)
    {
        $product = Product::with(['category', 'brand'])->where('slug', $slug)->firstOrFail();
        
        // Increment view count
        $product->increment('views_count');
        
        return response()->json($product);
    }

    public function mostViewed()
    {
        $products = Product::with(['category', 'brand'])
            ->orderBy('views_count', 'desc')
            ->limit(8)
            ->get();
            
        return response()->json($products);
    }
}
