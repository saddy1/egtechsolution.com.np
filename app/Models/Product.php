<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','slug','description',
        'price','sale_price',
        'quantity','stock_status',
        'category_id','brand_id',
        'sku','barcode',
        'meta_title','meta_description',
        'image','is_active','is_featured',
    ];

    protected $casts = [
        'is_active' => 'bool',
        'is_featured' => 'bool',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
    ];

    public function category() { return $this->belongsTo(Category::class); }
    public function brand()    { return $this->belongsTo(Brand::class); }

    // ✅ Add relation to specifications
    public function specifications()
    {
        return $this->hasMany(ProductSpecification::class);
    }

    // Optional: final price accessor
    public function getFinalPriceAttribute()
    {
        return $this->sale_price && $this->sale_price > 0
            ? $this->sale_price
            : $this->price;
    }

    // Optional: auto slug on creating if not provided
    protected static function booted()
    {
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name) . '-' . Str::random(6);
            }
        });
    }
}
