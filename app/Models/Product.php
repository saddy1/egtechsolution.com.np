<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'sale_price',
        'quantity',
        'stock_status',
        'category_id',
        'brand_id',
        'sku',
        'barcode',
        'meta_title',
        'meta_description',
        'image',
        'is_active',
        'is_featured',

    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'quantity' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    // Automatically generate slug from name
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name') && empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function specifications()
    {
        return $this->hasMany(ProductSpecification::class);
    }

    // Accessors
    public function getFinalPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    public function getDiscountPercentageAttribute()
    {
        if (!$this->sale_price || $this->sale_price >= $this->price) {
            return 0;
        }
        
        return round((($this->price - $this->sale_price) / $this->price) * 100);
    }

    public function getIsOnSaleAttribute()
    {
        return $this->sale_price && $this->sale_price < $this->price;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_status', 'in_stock')->where('quantity', '>', 0);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByBrand($query, $brandId)
    {
        return $query->where('brand_id', $brandId);
    }
}