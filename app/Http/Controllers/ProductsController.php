<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductSpecification;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->get('search', ''));

        $products = Product::with(['brand:id,name', 'category:id,name'])
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                })
                    ->orWhereHas('brand', function ($bq) use ($search) {
                        $bq->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('category', function ($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%");
                    });
            })
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        return view('backend.product.index', compact('products', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        return view('backend.product.create', compact('brands', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => ['required', 'string', 'max:255', 'unique:products,name'],
            'sku'              => ['required', 'string', 'max:255', 'unique:products,sku'],
            'price'            => ['required', 'numeric', 'min:0'],
            'sale_price'       => ['nullable', 'numeric', 'min:0'],
            'quantity'         => ['required', 'integer', 'min:0'],
            'stock_status'     => ['required', 'in:in_stock,out_of_stock'],
            'category_id'      => ['required', 'exists:categories,id'],
            'brand_id'         => ['required', 'exists:brands,id'],
            'description'      => ['required', 'string'],
            'specifications'   => ['nullable', 'string'],
            'meta_title'       => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'barcode'          => ['nullable', 'string', 'max:255'],
            'image'            => ['required', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:5120'],
            'is_active'        => ['nullable', 'boolean'],
            'is_featured'      => ['nullable', 'boolean'],
        ]);

        // slug (not in form) → generate unique-ish
        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(6);

        // store image on custom disk in "product" dir, filename = {sku}.{ext}
        $imageName = null;

        if ($request->hasFile('image')) {
            $ext = $request->file('image')->getClientOriginalExtension();
            $imageName = $request->sku . '.' . $ext;

            // Delete old file if exists (prevent duplicates)
            Storage::disk('public')->delete('product/' . $imageName);

            // Store new file
            $request->file('image')->storeAs('product', $imageName, 'public');
        }

        $product = Product::create([
            'name'             => $data['name'],
            'slug'             => $data['slug'],
            'description'      => $data['description'],
            'price'            => $data['price'],
            'sale_price'       => $data['sale_price'] ?? null,
            'quantity'         => $data['quantity'],
            'stock_status'     => $data['stock_status'],
            'category_id'      => $data['category_id'],
            'brand_id'         => $data['brand_id'],
            'sku'              => $data['sku'],
            'barcode'          => $data['barcode'] ?? null,
            'meta_title'       => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'image'            => $imageName, // just filename, as you had
            'is_active'        => $request->boolean('is_active', true),
            'is_featured'      => $request->boolean('is_featured', false),
        ]);

        if ($request->specifications) {
            $lines = explode("\n", $request->specifications);
            $specifications = [];

            foreach ($lines as $line) {
                $line = trim($line);
                if (!$line) continue;

                $parts = explode(":", $line, 2);
                if (count($parts) === 2) {
                    $specifications[] = [
                        'name'  => trim($parts[0]),
                        'value' => trim($parts[1]),
                    ];
                }
            }

            if (!empty($specifications)) {
                $product->specifications()->createMany($specifications);
            }
        }

        return redirect()
            ->route('product.index')
            ->with('success', 'Product created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        // if you want to prefill specs textarea, you can join them here (optional)
        // $specsText = $product->productSpecifications->map(fn($s) => "{$s->name}: {$s->value}")->implode("\n");

        return view('backend.product.edit', compact('product', 'brands', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'             => ['required', 'string', 'max:255', 'unique:products,name,' . $product->id],
            'slug'             => ['nullable', 'string', 'max:255', 'unique:products,slug,' . $product->id],
            'sku'              => ['required', 'string', 'max:255', 'unique:products,sku,' . $product->id],
            'price'            => ['required', 'numeric', 'min:0'],
            'sale_price'       => ['nullable', 'numeric', 'min:0'],
            'quantity'         => ['required', 'integer', 'min:0'],
            'stock_status'     => ['required', 'in:in_stock,out_of_stock'],
            'category_id'      => ['required', 'exists:categories,id'],
            'brand_id'         => ['required', 'exists:brands,id'],
            'description'      => ['required', 'string'],
            'specifications'   => ['nullable', 'string'],
            'meta_title'       => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'barcode'          => ['nullable', 'string', 'max:255'],
            'image'            => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:5120'],
            'is_active'        => ['nullable', 'boolean'],
            'is_featured'      => ['nullable', 'boolean'],
        ]);

        // keep slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = $product->slug ?? (Str::slug($data['name']) . '-' . Str::random(6));
        }

        // handle image replacement
        if ($request->hasFile('image')) {
            $ext = $request->file('image')->getClientOriginalExtension();
            $imageName = $request->sku . '.' . $ext;

            // Delete old image if exists
            if ($product->image) {
                Storage::disk('public')->delete('product/' . $product->image);
            }

            // Save new image
            $request->file('image')->storeAs('product', $imageName, 'public');
            $product->image = 'product/' . $imageName;
        }


        $product->update([
            'name'             => $data['name'],
            'slug'             => $data['slug'],
            'description'      => $data['description'],
            'price'            => $data['price'],
            'sale_price'       => $data['sale_price'] ?? null,
            'quantity'         => $data['quantity'],
            'stock_status'     => $data['stock_status'],
            'category_id'      => $data['category_id'],
            'brand_id'         => $data['brand_id'],
            'sku'              => $data['sku'],
            'barcode'          => $data['barcode'] ?? null,
            'meta_title'       => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'is_active'        => $request->boolean('is_active', $product->is_active),
            'is_featured'      => $request->boolean('is_featured', $product->is_featured),
        ]);

        // Replace specifications if provided (same behavior you had)
        if ($request->filled('specifications')) {
            $product->productSpecifications()->delete();
            $specs = $this->parseSpecifications($request->specifications);
            if (!empty($specs)) {
                $product->productSpecifications()->createMany($specs);
            }
        }

        return redirect()
            ->route('product.index')
            ->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
 public function destroy($id)
{
    try {
        $product = Product::findOrFail($id);

        // Delete attached specifications
        $product->specifications()->delete();

        // Delete image file if exists
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        // Delete the product
        $product->delete();

        return redirect()
            ->route('product.index')
            ->with('success', 'Product and image deleted successfully.');
    } catch (\Exception $e) {
        return redirect()
            ->route('product.index')
            ->with('error', 'Failed to delete product: ' . $e->getMessage());
    }
}


    /**
     * Parse "Key: Value" lines from textarea to array for createMany().
     */
    private function parseSpecifications($text)
    {
        $lines = preg_split('/\r\n|\r|\n/', $text);
        $specs = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') continue;

            $parts = explode(':', $line, 2);
            if (count($parts) === 2) {
                $specs[] = [
                    'name'  => trim($parts[0]),
                    'value' => trim($parts[1]),
                ];
            }
        }
        return $specs;
    }
}
