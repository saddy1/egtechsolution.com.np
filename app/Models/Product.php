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
     * The option buttons for the product page.
     *
     * @return array<string, array<int, array{value: string, product: Product, current: bool, exact: bool, in_stock: bool}>>
     *         option name => choices; "product" is where that choice leads, "exact" is false when
     *         picking it also changes another option because that combination is not sold.
     */
    public function variantChoices(): array
    {
        $variants = $this->variants()->filter(fn (Product $p) => ! empty($p->variant_options));
        $mine     = $this->variant_options ?? [];

        if ($variants->count() < 2 || ! $mine) {
            return [];
        }

        $choices = [];
        $names   = $variants->flatMap(fn (Product $p) => array_keys($p->variant_options))->unique();

        foreach ($names as $name) {
            $values = $variants->map(fn (Product $p) => $p->variant_options[$name] ?? null)->filter()->unique()
                ->sort(fn ($a, $b) => self::optionRank($a) <=> self::optionRank($b) ?: strnatcasecmp($a, $b));

            foreach ($values as $value) {
                // Prefer the variant that keeps the customer's other choices, then the cheapest.
                $target = $variants
                    ->filter(fn (Product $p) => ($p->variant_options[$name] ?? null) === $value)
                    ->sortBy(fn (Product $p) => [
                        -count(array_intersect_assoc(array_diff_key($p->variant_options, [$name => 1]), $mine)),
                        (float) $p->final_price,
                    ])->first();

                $choices[$name][] = [
                    'value'    => $value,
                    'product'  => $target,
                    'current'  => ($mine[$name] ?? null) === $value,
                    'exact'    => array_diff_key($target->variant_options, [$name => 1]) == array_diff_key($mine, [$name => 1]),
                    'in_stock' => $target->stock_status === 'in_stock' && $target->quantity > 0,
                ];
            }
        }

        // An option with a single value is not a choice.
        return array_filter($choices, fn ($options) => count($options) > 1);
    }

    /** Sort key that puts sizes in real order (512GB before 1TB); other values sort alphabetically. */
    private static function optionRank(string $value): float
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
