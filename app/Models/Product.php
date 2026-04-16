<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'user_id',
    ];

    protected $casts = [
        'price' => 'float',
        'stock' => 'integer',
    ];

    /**
     * Get the user who created this product.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
