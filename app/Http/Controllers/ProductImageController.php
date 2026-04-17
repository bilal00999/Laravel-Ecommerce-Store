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
        if ($product->image_path) {
            try {
                $oldPath = str_replace('storage/', '', $product->image_path);
                \Storage::disk('public')->delete($oldPath);
            } catch (\Exception $e) {
                // Ignore deletion errors
            }
        }

        // Store new image in storage/app/public/products
        $imagePath = $request->file('image')->store('products', 'public');
        
        // Store as relative path: storage/products/filename.jpg
        $product->update(['image_path' => 'storage/' . $imagePath]);

        return redirect()->route('products.index')
            ->with('success', 'Product image uploaded successfully!');
    }
}
