# Product Categories - Quick Reference

## Quick Start

### Adding Categories to Database (Admin)

```php
// In admin panel or seeder
Category::create(['name' => 'Electronics', 'description' => 'Electronic devices']);
Category::create(['name' => 'Clothing', 'description' => 'Apparel and fashion']);
```

### Creating a Product with Category

1. Go to: `http://yoursite.com/products/create`
2. Fill in product details
3. Select a category from dropdown (optional)
4. Click "Create Product"

### Filtering Products by Category

1. Go to: `http://yoursite.com/products`
2. Use sidebar category filter
3. Select category to filter

### Viewing Product with Category

- Navigate to: `http://yoursite.com/products/{id}`
- Category appears as breadcrumb: Home > Products > [Category] > [Product]
- Related products from same category shown below

## Code Examples

### In Blade Template - Display Product Category

```blade
@if($product->category)
    <p>Category: {{ $product->category->name }}</p>
@else
    <p>No category assigned</p>
@endif
```

### In Controller - Get Products by Category

```php
$products = Product::where('category_id', $categoryId)->get();
```

### In Controller - Get All Related Products

```php
$relatedProducts = Product::where('category_id', $product->category_id)
    ->where('id', '!=', $product->id)
    ->limit(4)
    ->get();
```

### In Controller - Get Category with Products

```php
$category = Category::with('products')->find($id);
foreach($category->products as $product) {
    echo $product->name;
}
```

## Database

### Add a Category Via Database CLI

```sql
INSERT INTO categories (name, description, created_at, updated_at)
VALUES ('Laptops', 'Computer laptops and notebooks', NOW(), NOW());
```

### Get All Products in a Category

```sql
SELECT * FROM products WHERE category_id = 1;
```

### Count Products by Category

```sql
SELECT category_id, COUNT(*) as count
FROM products
GROUP BY category_id;
```

## Routes

| Method | Route                  | Description                              |
| ------ | ---------------------- | ---------------------------------------- |
| GET    | `/products`            | List all products (with category filter) |
| GET    | `/products?category=1` | List products in category 1              |
| GET    | `/products/create`     | Show create form (with category select)  |
| POST   | `/products`            | Store new product (with category)        |
| GET    | `/products/{id}`       | Show product (with category and related) |
| GET    | `/products/{id}/edit`  | Edit form (with category select)         |
| PUT    | `/products/{id}`       | Update product (with category)           |
| DELETE | `/products/{id}`       | Delete product                           |

## Validation

When creating/updating products:

```
category_id must be:
- nullable (can be empty)
- exist in categories table (if provided)
```

## Common Issues & Solutions

| Issue                  | Solution                                                         |
| ---------------------- | ---------------------------------------------------------------- |
| Category not showing   | Check if `$product->category` is loaded with `.load('category')` |
| Filter not working     | Verify `category` query parameter is correct name                |
| Can't select category  | Ensure categories exist in database                              |
| Foreign key error      | Make sure category exists before assigning to product            |
| Related products empty | Check product actually has a category assigned                   |

## Performance Tips

### Query Optimization

```php
// Good - Load category with product
$product = Product::with('category')->find($id);

// Bad - N+1 query problem
$product = Product::find($id);
echo $product->category->name; // Extra query!
```

### Filtering Performance

```php
// Get categories list once, reuse in loop
$categories = Category::all();
$products = Product::with('category')->paginate();

foreach($products as $product) {
    echo $product->category->name; // No extra queries!
}
```

## Useful Artisan Commands

```bash
# Create category model (already done)
php artisan make:model Category -m

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Fresh database with migrations
php artisan migrate:fresh

# List tables in database
php artisan tinker
# Then: DB::table('categories')->get();
```

## Testing

### Verify Category Relationship

```php
// In tinker: php artisan tinker
$category = Category::first();
$category->products()->count(); // Should show count

$product = Product::first();
$product->category; // Should show category or null
```

### Check Filtering Works

```
GET /products?category=1
Response should only show products with category_id = 1
```

## File Locations

| Component   | File                                         |
| ----------- | -------------------------------------------- |
| Model       | `app/Models/Category.php`                    |
| Model       | `app/Models/Product.php`                     |
| Controller  | `app/Http/Controllers/ProductController.php` |
| Create Form | `resources/views/products/create.blade.php`  |
| Edit Form   | `resources/views/products/edit.blade.php`    |
| List View   | `resources/views/products/index.blade.php`   |
| Detail View | `resources/views/products/show.blade.php`    |
| Migration   | `database/migrations/*categories*`           |
| Migration   | `database/migrations/*category_id*`          |

## API Endpoints

```
Create Product with Category:
POST /products
{
    "name": "Laptop",
    "description": "Gaming laptop",
    "price": 1299.99,
    "stock": 5,
    "category_id": 1
}

Edit Product Category:
PUT /products/5
{
    "category_id": 2
}

Filter by Category:
GET /products?category=1&search=gaming&min_price=100&max_price=2000
```

## Features Summary

✅ Create products with optional category
✅ Edit product category
✅ Filter products by category
✅ View category on product page
✅ Category breadcrumb navigation
✅ Related products from same category
✅ Admin panel category info
✅ Client-side category filtering
✅ Improved form UI with cards
✅ Proper error handling

## Related Documentation

- Full guide: `CATEGORIES_FEATURE.md`
- Authentication guide: `AUTHENTICATION.md`
- Authorization guide: `GATES_AND_POLICIES_GUIDE.md`
- Testing guide: `TESTING_GUIDE.md`
