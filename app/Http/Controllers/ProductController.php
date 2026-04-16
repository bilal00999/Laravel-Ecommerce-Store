<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     * Everyone can view products
     */
    public function index()
    {
        $products = Product::all();
        return view('products.index', ['products' => $products]);
    }

    /**
     * Show the form for creating a new product.
     * Only admins can create products
     * 
     * Using Policy: $this->authorize('create', Product::class);
     */
    public function create()
    {
        // Authorize using policy
        $this->authorize('create', Product::class);

        return view('products.create');
    }

    /**
     * Store a newly created product in database.
     */
    public function store(Request $request)
    {
        // Authorize using policy
        $this->authorize('create', Product::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        // Add current user as creator
        $validated['user_id'] = auth()->id();
        $validated['slug'] = str()->slug($validated['name']);

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        // Anyone can view, but we can use policy if needed
        // $this->authorize('view', $product);

        return view('products.show', ['product' => $product]);
    }

    /**
     * Show the form for editing the specified product.
     * Only product owner or admin can edit
     * 
     * Using Policy with Route Model Binding: 
     * $this->authorize('update', $product);
     */
    public function edit(Product $product)
    {
        // Authorize using policy with product instance
        $this->authorize('update', $product);

        return view('products.edit', ['product' => $product]);
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product)
    {
        // Authorize using policy
        $this->authorize('update', $product);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $validated['slug'] = str()->slug($validated['name']);
        $product->update($validated);

        return redirect()->route('products.show', $product)
            ->with('success', 'Product updated successfully');
    }

    /**
     * Delete the specified product.
     * Only product owner or admin can delete
     */
    public function destroy(Product $product)
    {
        // Authorize using policy
        $this->authorize('delete', $product);

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully');
    }

    /**
     * Admin-only action using Gate
     * Show admin statistics
     */
    public function adminStats()
    {
        // Using Gate directly
        if (!auth()->check() || !auth()->user()->can('admin')) {
            throw new AuthorizationException();
        }

        $totalProducts = Product::count();
        $totalUsers = \App\Models\User::count();

        return view('products.admin-stats', [
            'totalProducts' => $totalProducts,
            'totalUsers' => $totalUsers,
        ]);
    }

    /**
     * Alternative: Using Gate::denies()
     */
    public function managersOnly()
    {
        if (\Illuminate\Support\Facades\Gate::denies('moderator')) {
            throw new AuthorizationException('Access denied');
        }

        // ... manager logic
    }

    /**
     * Alternative: Using Gate::authorize()
     */
    public function settingsPage()
    {
        // Will throw AuthorizationException if check fails
        \Illuminate\Support\Facades\Gate::authorize('manage-settings');

        return view('products.settings');
    }
}
