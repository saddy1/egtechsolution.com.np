<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\ProductSpecification;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)

    {
        $search = $request['search'] ?? "";
        if ($search != "") {
            // where clause
            $products = Product::where('name', 'LIKE', "%$search%")->orWhere('brand', 'LIKE', "% $search%")->orWhere('rate', 'LIKE', "%$search%")->orWhere('description', 'LIKE', "%$search%")->paginate(7);
        } else {
            $products = Product::orderBy('created_at', 'desc')->paginate(7);
        }

        #it retrive all data from top to bottom
        return view('backend.product.index')->with(compact('products', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::all();
        $categories = Category::all();
        $specifications = [];
        return view('backend.product.create', compact('brands', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|unique:products,name',
        'sku' => 'required|string|unique:products,sku',
        'price' => 'required|numeric|min:0',
        'sale_price' => 'nullable|numeric|min:0',
        'quantity' => 'required|integer|min:0',
        'stock_status' => 'required|in:in_stock,out_of_stock',
        'category_id' => 'required|exists:categories,id',
        'brand_id' => 'required|exists:brands,id',
        'description' => 'required|string',
        'specifications' => 'nullable|string',
        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ]);

    // Handle image upload
    $imagePath = null;
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = $request->sku . '.' . $image->getClientOriginalExtension();
        $imagePath = $image->storeAs('product', $imageName, 'custom');
    }

    // Create product
    $product = Product::create([
        'name' => $request->name,
        'sku' => $request->sku,
        'description' => $request->description,
        'price' => $request->price,
        'sale_price' => $request->sale_price,
        'quantity' => $request->quantity,
        'stock_status' => $request->stock_status,
        'category_id' => $request->category_id,
        'brand_id' => $request->brand_id,
        'barcode' => $request->barcode,
        'meta_title' => $request->meta_title,
        'meta_description' => $request->meta_description,
        'image' => $imageName ?? null,
        'is_active' => $request->boolean('is_active', true),
        'is_featured' => $request->boolean('is_featured', false),
    ]);

    // Store specifications in separate table
    if ($request->specifications) {
        $specificationsArray = $this->parseSpecifications($request->specifications);
        foreach ($specificationsArray as $spec) {
            $product->productSpecifications()->create($spec);
        }
    }

    return redirect()
        ->route('product.index')
        ->with('success', 'Product created successfully');
}

private function parseSpecifications($text)
{
    $lines = explode("\n", $text);
    $specifications = [];

    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;
        
        $parts = explode(":", $line, 2);
        if (count($parts) === 2) {
            $specifications[] = [
                'name' => trim($parts[0]),
                'value' => trim($parts[1]),
            ];
        }
    }

    return $specifications;
}


    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $brands = Brand::all();
        $category=Category::all();

        return view('backend.product.edit', ['product' => $product, 'brands' => Brand::latest()->get(),'category'=>Category::latest()->get()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
{
    // Validate input, including the new specifications field
    $request->validate([
        'rate' => 'required|numeric',
        'discount' => 'required|numeric',
        'quantity' => 'required|numeric',
        'brand' => 'required',
        'category' => 'required',
        'description' => 'required|string',
        'specifications' => 'required|string',
    ]);

    // Update the product data
    $product->description = $request->description;
    $product->rate = $request->rate;
    $product->brand = $request->brand;
    $product->discount = $request->discount;
    $product->quantity = $request->quantity;
    $product->category = $request->category;
    $product->save();

    // Parse and update the product specifications
    $specificationsText = $request->input('specifications');
    $specificationsArray = $this->parseSpecifications($specificationsText);

    // Delete existing specifications and create new ones
    $product->specifications()->delete();
    $product->specifications()->createMany($specificationsArray);

    return redirect()->route('product.index')->with('success', 'Product updated successfully');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Delete associated product specifications
        $product->specifications()->delete();

        // Delete the associated photo
        $photoPath = 'product/' . $product->name . '.png';
        Storage::disk('custom')->delete($photoPath);

        //  delete the product itself
        $product->delete();

        return redirect()->route('product.index')->with('success', 'Product deleted successfully');
    }
}
