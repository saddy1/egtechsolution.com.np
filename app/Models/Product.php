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
        'keywords',
        'meta_title','meta_description',
        'image','is_active','is_featured',
        'cost_price','supplier_id', // Added fields
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
   public function supplier()
{
    return $this->belongsTo(Supplier::class); // uses supplier_id by default
}

    /** Extra gallery photos; the main one is the "image" column. */
    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order')->orderBy('id');
    }

    /** Every photo of the product, main one first, as paths under public/. */
    public function gallery(): array
    {
        return array_values(array_filter([$this->image, ...$this->images->pluck('path')]));
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
