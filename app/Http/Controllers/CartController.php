<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;


class CartController extends Controller
{
    public function add(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'product_id' => ['required','exists:products,id'],
        'quantity'   => ['required','integer','min:1','max:5'],
    ]);

    $product = Product::findOrFail($request->product_id);

    // check stock
    if ($product->quantity < 1) {
        return back()->withErrors(['quantity' => 'This item is out of stock.']);
    }

    // enforce max per order = min(5, available stock)
    $maxPerOrder = min(5, $product->quantity);

    // existing cart line
    $cartItem = Cart::where('user_id', $user->id)
        ->where('product_id', $product->id)
        ->first();

    $newQty = $request->quantity;

    if ($cartItem) {
        $newQty = $cartItem->quantity + $request->quantity;
    }

    // clamp to allowed max
    if ($newQty > $maxPerOrder) {
        $newQty = $maxPerOrder;
    }

    // final guard against over-stock
    if ($newQty > $product->quantity) {
        return back()->withErrors([
            'quantity' => "Only {$product->quantity} in stock."
        ]);
    }

    if ($cartItem) {
        $cartItem->update(['quantity' => $newQty]);
    } else {
        Cart::create([
            'user_id'    => $user->id,
            'product_id' => $product->id,
            'quantity'   => $newQty,
        ]);
    }

    return back()->with('success', 'Item added to cart successfully');
}


    public function show()
{
    $user = Auth::user();
    $cartItems = Cart::where('user_id', $user->id)->get();
    $isEmptyCart = $cartItems->isEmpty();

    // Get the product details for each cart item
    $products = $cartItems->map(function ($item) {
        $product = Product::find($item->product_id);
        return [
            'id' => $item->id,
            'name' => $product->name,
            'rate' => $product->rate,
            'quantity' => $item->quantity,
            'total' => $product->rate * $item->quantity,
        ];
    });

    // Calculate subtotal and total
    $subtotal = $products->sum('total');
    $total = $subtotal;

    // Fetch past orders for the user
    $orders = Order::where('user_id', $user->id)
    ->with('items') // Eager load related items
    ->take(10)
    ->orderBy('created_at', 'desc') // Sort by creation date, newest first
    ->get();

    return view('frontend.cart', [
        'products' => $products,
        'isEmptyCart' => $isEmptyCart,
        'subtotal' => number_format($subtotal, 2),
        'total' => number_format($total, 2),
        'orders' => $orders,
    ]);
}

    public function remove(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'id' => 'required|exists:carts,id',
        ]);

        $cartItem = Cart::where('user_id', $user->id)
            ->where('id', $request->id)
            ->firstOrFail();

        $cartItem->delete();

        return redirect()->route('cart.show')->with('success', 'Item removed from cart successfully');
    }

public function updateQuantity(Request $request, $id)
{
    $user = Auth::user();

    $request->validate([
        'quantity_action' => 'required|in:increase,decrease',
    ]);

    $cartItem = Cart::where('user_id', $user->id)
        ->where('id', $id)
        ->firstOrFail();

    $product = Product::findOrFail($cartItem->product_id);

    // If product is completely out of stock
    if ($product->quantity < 1) {
        return back()->withErrors(['quantity' => 'This item is currently out of stock.']);
    }

    $maxPerOrder = min(5, $product->quantity);

    if ($request->quantity_action === 'increase') {
        if ($cartItem->quantity < $maxPerOrder) {
            $cartItem->quantity++;
        } else {
            return back()->withErrors([
                'quantity' => $product->quantity <= $cartItem->quantity
                    ? 'Out of stock'
                    : "You can order a maximum of {$maxPerOrder} items.",
            ]);
        }
    } elseif ($request->quantity_action === 'decrease') {
        if ($cartItem->quantity > 1) {
            $cartItem->quantity--;
        }
    }

    // Double-check against stock
    if ($cartItem->quantity > $product->quantity) {
        return back()->withErrors([
            'quantity' => 'Out of stock',
        ]);
    }

    $cartItem->save();

    return back()->with('success', 'Quantity updated successfully');
}


    public function index()
    {
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->get();

        // Map product details to cart items
        $cartItems = $cartItems->map(function ($item) {
            $product = Product::find($item->product_id);
            return [
                'id' => $item->id,
                'name' => $product->name,
                'rate' => $product->rate,
                'quantity' => $item->quantity,
                'total' => $product->rate * $item->quantity,
            ];
        });

        $subtotal = $cartItems->sum('total');
        $total = $subtotal;

        return view('frontend.checkout', [
            'cartItems' => $cartItems,
            'subtotal' => number_format($subtotal, 2),
            'total' => number_format($total, 2),
        ]);
    }

    public function store(Request $request)
    {
        // Define validation rules
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|numeric', // Phone should be a number
            'email' => 'required|email',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'zip' => 'required|numeric', // Zip code should be a number
            'payment_method' => 'required|string',
        ]);
    
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->get();
    
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.show')->with('error', 'Your cart is empty!');
        }
    
        // Format cart items
        $cartItems = $cartItems->map(function ($item) {
            $product = Product::find($item->product_id);
            return [
                'product_id' => $item->product_id,
                'name' => $product->name,
                'rate' => $product->rate,
                'quantity' => $item->quantity,
                'total' => $product->rate * $item->quantity,
            ];
        });
    
        // Calculate the total from cart items
        $total = $cartItems->sum('total');
    
        // Create the order
        $order = Order::create([
            
            'user_id' => $user->id,
            'name' => $validatedData['name'],
            'phone' => $validatedData['phone'],
            'email' => $validatedData['email'],
            'address' => $validatedData['address'],
            'city' => $validatedData['city'],
            'country' => $validatedData['country'],
            'zip' => $validatedData['zip'],
            'payment_method' => $validatedData['payment_method'],
            'total' => $total,
        ]);
    
        // Create OrderItems
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'product_name' => $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['rate'],
                'total' => $item['total'],
            ]);
        }
    
        // Clear the cart
        Cart::where('user_id', $user->id)->delete();
    
        // Return the success view with order details
        return view('frontend.success', [
            'successMessage' => 'Order placed successfully!',
            'order' => $order,
            'cartItems' => $cartItems,
            'subtotal' => $cartItems->sum('total'),
            'total' => $total,
        ]);
    }
    



   public function clear()
{
    $user = Auth::user();

    // Remove all cart items for this user
    Cart::where('user_id', $user->id)->delete();

    // Flash message for Blade to show
    return redirect()->back()->with('success', 'All items removed from your cart successfully.');
}

}
