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
        'variant_group_id','variant_options',
    ];

    protected $casts = [
        'is_active' => 'bool',
        'is_featured' => 'bool',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'variant_options' => 'array',
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

    public function variantGroup()
    {
        return $this->belongsTo(VariantGroup::class);
    }

    /**
     * For listings: of several products in one variant group, keep only the
     * cheapest active one, so the shop shows one card per model.
     */
    public function scopeListed($query)
    {
        $price = fn (string $t) => "COALESCE(NULLIF($t.sale_price, 0), $t.price)";

        return $query->where(function ($q) use ($price) {
            $q->whereNull('products.variant_group_id')
                ->orWhereNotExists(function ($cheaper) use ($price) {
                    $cheaper->from('products as s')
                        ->whereColumn('s.variant_group_id', 'products.variant_group_id')
                        ->where('s.is_active', true)
                        ->where(function ($c) use ($price) {
                            $c->whereRaw($price('s').' < '.$price('products'))
                                ->orWhere(fn ($tie) => $tie->whereRaw($price('s').' = '.$price('products'))->whereColumn('s.id', '<', 'products.id'));
                        });
                });
        });
    }

    /** Active products in the same variant group, this one included. */
    public function variants()
    {
        if (! $this->variant_group_id) {
            return collect();
        }

        return static::where('variant_group_id', $this->variant_group_id)->where('is_active', true)->orderBy('id')->get();
    }

    /**
     * The variant cards for the product page: every active variant of this
     * model (this one included), smallest first.
     *
     * @return \Illuminate\Support\Collection<int, array{product: Product, label: string, current: bool, in_stock: bool}>
     */
    public function variantCards()
    {
        $variants = $this->variants()->filter(fn (Product $p) => ! empty($p->variant_options));

        if ($variants->count() < 2 || empty($this->variant_options)) {
            return collect();
        }

        return $variants
            ->sortBy(fn (Product $p) => [
                self::sizeRank($p->variant_options['RAM'] ?? ''),
                self::sizeRank($p->variant_options['Storage'] ?? ''),
                (float) $p->final_price,
            ])
            ->map(fn (Product $p) => [
                'product'  => $p,
                'label'    => implode(' + ', $p->variant_options),
                'current'  => $p->id === $this->id,
                'in_stock' => $p->stock_status === 'in_stock' && $p->quantity > 0,
            ])->values();
    }

    /**
     * Split a long listing name into a short title and its feature list, e.g.
     * "Acer Swift Neo 14 (Core Ultra 5 | 16GB RAM | Silver)" => ["Acer Swift Neo 14", ["Core Ultra 5", "16GB RAM", "Silver"]].
     *
     * @return array{0: string, 1: string[]}
     */
    public function titleParts(): array
    {
        $name = trim($this->name);

        // Drop an automatic "(16GB / 1TB SSD)" variant suffix: the option buttons already say it.
        if (preg_match('/^(.*\|.*\))\s*\([^()|]*\)$/u', $name, $m)) {
            $name = $m[1];
        }

        $open = strpos($name, '(');
        $bar  = strpos($name, '|');

        if ($open !== false && $bar !== false && $open < $bar && str_ends_with($name, ')')) {
            // "Model (feature | feature | ...)": the title is what stands before the bracket.
            [$title, $features] = [trim(substr($name, 0, $open)), substr($name, $open + 1, -1)];
        } else {
            $parts = explode('|', $name, 2);
            [$title, $features] = [trim($parts[0]), $parts[1] ?? ''];
        }

        return [$title ?: $name, array_values(array_filter(array_map('trim', explode('|', $features))))];
    }

    /** Sort key that puts sizes in real order (512GB before 1TB); anything else counts as 0. */
    private static function sizeRank(string $value): float
    {
        return preg_match('/^\s*(\d+(?:\.\d+)?)\s*(MB|GB|TB)\b/i', $value, $m)
            ? (float) $m[1] * ['MB' => 1, 'GB' => 1024, 'TB' => 1024 * 1024][strtoupper($m[2])]
            : 0;
    }

    /** Extra gallery photos; the main one is the "image" column. */
    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order')->orderBy('id');
    }

    /** Every photo of the product, main one first, as paths under public/. */
    public function gallery(): array
    {
        $extra = $this->images->pluck('path');

        // A variant without extra photos of its own borrows them from the others of its model.
        if ($extra->isEmpty() && $this->variant_group_id) {
            $extra = ProductImage::whereIn('product_id', static::where('variant_group_id', $this->variant_group_id)->select('id'))
                ->orderBy('product_id')->orderBy('sort_order')->pluck('path');
        }

        return array_values(array_unique(array_filter([$this->image, ...$extra])));
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
