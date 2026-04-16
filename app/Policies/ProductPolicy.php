<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    /**
     * Determine whether the user can view any products.
     * (Everyone can view products)
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the product.
     * (Everyone can view a specific product)
     */
    public function view(?User $user, Product $product): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create products.
     * (Only admin users can create)
     * 
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        // Only admins can create products
        return $user->role === 'admin' || $user->is_admin;
    }

    /**
     * Determine whether the user can update the product.
     * (Only product owner or admin can edit)
     * 
     * @param User $user
     * @param Product $product
     * @return bool
     */
    public function update(User $user, Product $product): bool
    {
        // Admin can update any product
        if ($user->role === 'admin' || $user->is_admin) {
            return true;
        }

        // Owner can update their own product
        return $user->id === $product->user_id;
    }

    /**
     * Determine whether the user can delete the product.
     * (Only product owner or admin can delete)
     * 
     * @param User $user
     * @param Product $product
     * @return bool
     */
    public function delete(User $user, Product $product): bool
    {
        // Admin can delete any product
        if ($user->role === 'admin' || $user->is_admin) {
            return true;
        }

        // Owner can delete their own product
        return $user->id === $product->user_id;
    }

    /**
     * Determine whether the user can restore the product.
     */
    public function restore(User $user, Product $product): bool
    {
        return $user->role === 'admin' || $user->is_admin;
    }

    /**
     * Determine whether the user can permanently delete the product.
     */
    public function forceDelete(User $user, Product $product): bool
    {
        return $user->role === 'admin' || $user->is_admin;
    }
}
