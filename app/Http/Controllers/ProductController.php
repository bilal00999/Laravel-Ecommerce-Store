<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProductController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of products with filtering and pagination.
     * Everyone can view products
     * 
     * Query Parameters:
     * - search: Search by name/description
     * - category: Filter by category ID
     * - min_price: Minimum price filter
     * - max_price: Maximum price filter
     * - sort: Sort by (latest, popular, price_low, price_high)
     * - per_page: Items per page (default 12)
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $categoryId = $request->query('category');
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        $sort = $request->query('sort', 'latest');
        $perPage = $request->query('per_page', 12);

        // Start query
        $query = Product::query();

        // Apply filters
        $query->search($search);
        $query->byCategory($categoryId);
        $query->byPriceRange($minPrice, $maxPrice);

        // Apply sorting
        switch ($sort) {
            case 'popular':
                // You can add views count or sales count
                $query->orderBy('stock', 'desc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Paginate results
        $products = $query->with('category', 'user')
                         ->paginate($perPage);

        // Get all categories for filter dropdown
        $categories = Category::all();

        // Get price range for filter display
        $allProducts = Product::query();
        $minPriceAvailable = $allProducts->min('price') ?? 0;
        $maxPriceAvailable = $allProducts->max('price') ?? 1000;

        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
            'search' => $search,
            'categoryId' => $categoryId,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'sort' => $sort,
            'minPriceAvailable' => $minPriceAvailable,
            'maxPriceAvailable' => $maxPriceAvailable,
        ]);
    }

    /**
     * Show the form for creating a new product.
     * Only admins can create products
     */
    public function create()
    {
        $this->authorize('create', Product::class);

        $categories = Category::all();
        return view('products.create', ['categories' => $categories]);
    }

    /**
     * Store a newly created product in database.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Product::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
        ]);

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
        // Load relationships
        $product->load('category', 'user');

        // Get related products (same category, different product)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('products.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }

    /**
     * Show the form for editing the specified product.
     * Only product owner or admin can edit
     */
    public function edit(Product $product)
    {
        $this->authorize('update', $product);

        $categories = Category::all();
        return view('products.edit', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
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
        $this->authorize('delete', $product);

        // Check if product has any associated order items
        if ($product->orderItems()->exists()) {
            return redirect()->route('products.index')
                ->with('error', 'Cannot delete product with existing orders. This product appears in ' . $product->orderItems()->count() . ' order(s).');
        }

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
        if (!auth()->check() || !auth()->user()->can('admin')) {
            throw new AuthorizationException();
        }

        $totalProducts = Product::count();
        $totalUsers = \App\Models\User::count();
        $totalCategories = Category::count();

        return view('products.admin-stats', [
            'totalProducts' => $totalProducts,
            'totalUsers' => $totalUsers,
            'totalCategories' => $totalCategories,
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

        $products = Product::all();
        return view('products.managers', ['products' => $products]);
    }

    /**
     * Alternative: Using Gate::authorize()
     */
    public function settingsPage()
    {
        \Illuminate\Support\Facades\Gate::authorize('manage-settings');

        return view('products.settings');
    }
}

