<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    /**
     * Show the form for uploading a product image
     */
    public function edit(Product $product)
    {
        return view('products.edit-image', compact('product'));
    }

    /**
     * Store the uploaded product image
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Delete old image if exists
        if ($product->image_path && file_exists(public_path($product->image_path))) {
            unlink(public_path($product->image_path));
        }

        // Store new image
        $imagePath = $request->file('image')->store('products', 'public');
        
        $product->update(['image_path' => 'storage/' . $imagePath]);

        return redirect()->route('products.index')
            ->with('success', 'Product image uploaded successfully!');
    }
}
