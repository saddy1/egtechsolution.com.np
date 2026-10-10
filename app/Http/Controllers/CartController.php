<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlacedAdmin;
use App\Mail\OrderPlacedCustomer;


use Illuminate\Support\Str;


class CartController extends Controller
{

    public function show()
    {
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->get();
        $isEmptyCart = $cartItems->isEmpty();

        $products = $cartItems->map(function ($item) {
            $p = Product::find($item->product_id);

            $unit = $p ? (float) ($p->final_price ?? $p->price ?? 0) : 0; // <- use final_price
            $imageUrl = $p->image;
            return [
                'id'         => $item->id,           // cart row id
                'product_id' => $item->product_id,
                'name'       => $p?->name ?? 'N/A',
                'rate'       => $unit,               // <- now correct
                'quantity'   => $item->quantity,
                'total'      => $unit * $item->quantity,
                'available'  => (int)($p?->quantity ?? 0),
                'image_url'  => $imageUrl,
            ];
        });

        $subtotal = $products->sum('total');
        $total    = $subtotal;

        $orders = Order::where('user_id', $user->id)
            ->with('items')
            ->orderByDesc('created_at')->take(10)->get();

        return view('frontend.cart', [
            'products'    => $products,
            'isEmptyCart' => $isEmptyCart,
            'subtotal'    => number_format($subtotal, 2),
            'total'       => number_format($total, 2),
            'orders'      => $orders,
        ]);
    }


    public function add(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity'   => ['required', 'integer', 'min:1', 'max:5'],
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

        // Out of stock entirely
        if ($product->quantity < 1) {
            return back()->withErrors([
                "qty_{$id}" => 'This item is currently out of stock.',
            ]);
        }

        $maxPerOrder = min(5, $product->quantity);

        if ($request->quantity_action === 'increase') {
            if ($cartItem->quantity < $maxPerOrder) {
                $cartItem->quantity++;
            } else {
                return back()->withErrors([
                    "qty_{$id}" => $product->quantity <= $cartItem->quantity
                        ? 'Out of stock'
                        : "You can order a maximum of {$maxPerOrder} items.",
                ]);
            }
        } else { // decrease
            if ($cartItem->quantity > 1) {
                $cartItem->quantity--;
            }
        }

        // Final guard
        if ($cartItem->quantity > $product->quantity) {
            return back()->withErrors([
                "qty_{$id}" => 'Out of stock',
            ]);
        }

        $cartItem->save();

        return back()->with('success', 'Quantity updated successfully');
    }



    public function index()
    {
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->get();

        $cartItems = $cartItems->map(function ($item) {
            $p = Product::find($item->product_id);
            $unit = $p ? (float) ($p->final_price ?? $p->price ?? 0) : 0;

            return [
                'id'       => $item->id,
                'name'     => $p?->name ?? 'N/A',
                'rate'     => $unit, // <- use final price
                'quantity' => $item->quantity,
                'total'    => $unit * $item->quantity,
            ];
        });

        $subtotal = $cartItems->sum('total');
        $total    = $subtotal;

        return view('frontend.checkout', [
            'cartItems' => $cartItems,
            'subtotal'  => number_format($subtotal, 2),
            'total'     => number_format($total, 2),
        ]);
    }

    public function store(Request $request)
    {
        // 1) Validate checkout form
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'phone'          => ['required', 'string', 'max:40'],
            'email'          => ['required', 'email', 'max:150'],
            'address'        => ['required', 'string', 'max:2000'],
            'city'           => ['required', 'string', 'max:255'],
            'country'        => ['required', 'string', 'max:255'],
            'zip'            => ['required', 'string', 'max:20'],
            'payment_method' => ['required', 'in:cod,debit,paypal'],
        ]);

        $user = Auth::user();

        // 2) Load cart with products
        $cartRows = Cart::where('user_id', $user->id)->with('product')->get();
        if ($cartRows->isEmpty()) {
            return redirect()->route('cart.show')->with('error', 'Your cart is empty!');
        }

        // 3) Build normalized lines + pre-check stock/prices
        $lines = [];
        $subtotal = 0;

        foreach ($cartRows as $row) {
            $p = $row->product;

            if (!$p) {
                return redirect()->route('cart.show')
                    ->withErrors(['cart' => "A product in your cart no longer exists."]);
            }

            // If your Product model has no is_active, treat as active
            $isActive = property_exists($p, 'is_active') ? (bool)$p->is_active : true;
            if (!$isActive) {
                return redirect()->route('cart.show')
                    ->withErrors(['cart' => "Product {$p->name} is unavailable."]);
            }

            $unitPrice = $p->final_price ?? (($p->sale_price && $p->sale_price > 0) ? $p->sale_price : $p->price);
            if ($unitPrice === null) {
                return redirect()->route('cart.show')
                    ->withErrors(['cart' => "Price is missing for product: {$p->name}."]);
            }

            $availableQty = (int)($p->quantity ?? 0);
            $qty = max(1, (int)$row->quantity);

            if ($qty > $availableQty) {
                return redirect()->route('cart.show')
                    ->withErrors(["quantity_item_{$row->id}" => "Only {$availableQty} left in stock for {$p->name}."]);
            }

            $lineTotal = $unitPrice * $qty;
            $subtotal += $lineTotal;

            $lines[] = [
                'cart_id'      => $row->id,
                'product_id'   => $p->id,
                'product_name' => $p->name,
                'image'        => $p->image,
                'qty'          => $qty,
                'unit_price'   => (float)$unitPrice,
                'line_total'   => (float)$lineTotal,
            ];
        }

        // For now, total == subtotal
        $total = $subtotal;

        try {
            $order = DB::transaction(function () use ($user, $validated, $lines, $subtotal, $total) {

                // Build attributes only for columns that actually exist
                $attrs = [
                    'user_id'        => $user->id,
                    'name'           => $validated['name'],
                    'phone'          => $validated['phone'],
                    'email'          => $validated['email'],
                    'address'        => $validated['address'],
                    'city'           => $validated['city'],
                    'country'        => $validated['country'],
                    'zip'            => $validated['zip'],
                    'payment_method' => $validated['payment_method'],
                    'total'          => $total, // guaranteed column in your migration
                ];

                if (Schema::hasColumn('orders', 'subtotal')) {
                    $attrs['subtotal'] = $subtotal;
                }
                if (Schema::hasColumn('orders', 'order_status')) {
                    // Use lowercase for consistency; your DB default may be 'Pending' which is fine too.
                    $attrs['order_status'] = Order::STATUS_PENDING ?? 'pending';
                }
                if (Schema::hasColumn('orders', 'placed_at')) {
                    $attrs['placed_at'] = now();
                }

                $order = Order::create($attrs);
                if (!$order) {
                    throw new \RuntimeException("Failed to create order record.");
                }
               


                // Insert items + decrement stock under row lock
                foreach ($lines as $ln) {
                    $prod = Product::lockForUpdate()->find($ln['product_id']);
                    if (!$prod) {
                        throw new \RuntimeException("A product was removed: {$ln['product_name']}.");
                    }

                    $available = (int)($prod->quantity ?? 0);
                    if ($available < $ln['qty']) {
                        throw new \RuntimeException("Only {$available} left in stock for {$prod->name}.");
                    }

                    OrderItem::create([
                        'order_id'     => $order->id,
                        'product_id'   => $prod->id,
                        'product_name' => $ln['product_name'],

                        'quantity'     => $ln['qty'],
                        'price'        => $ln['unit_price'],
                        'total'        => $ln['line_total'],
                        'status'       => OrderItem::STATUS_PENDING ?? 'pending',
                    ]);

                    // Decrement stock & flip status if your model uses it
                    $prod->quantity = $available - $ln['qty'];
                    if (property_exists($prod, 'stock_status') && $prod->quantity <= 0) {
                        $prod->stock_status = 'out_of_stock';
                    }
                    $prod->save();
                }

                // Clear the user's cart
                Cart::where('user_id', $user->id)->delete();
                $order->loadMissing(['items', 'user']);

                // Send email to admin

                 if ($order) {
                    $order = Order::find($order->id);
                    try {
                        // Admins
                        Mail::to('egtechsolution1@gmail.com')
                            ->cc('sadanand@ioepc.edu.np')
                            ->send(new OrderPlacedAdmin($order));
                    } catch (\Throwable $e) {
                        \Log::error('Admin email send failed', ['order_id' => $order->id, 'msg' => $e->getMessage()]);
                    }

                    try {
                        // Customer
                        Mail::to($order->email)->send(new OrderPlacedCustomer($order));
                    } catch (\Throwable $e) {
                        \Log::error('Customer email send failed', ['order_id' => $order->id, 'msg' => $e->getMessage()]);
                    }
                }
           


                return $order->load('items');
            });
        } catch (\Throwable $e) {
            // Log the real reason to storage/logs/laravel.log
            Log::error('Checkout failure', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return redirect()->route('cart.show')
                ->withErrors(['checkout' => 'Failed to place order. Please try again.']);
        }

        // 5) Success page
        $cartItemsForView = collect($lines)->map(fn($ln) => [
            'name'     => $ln['product_name'],
            'image'    => $ln['image'],
            'rate'     => $ln['unit_price'],
            'quantity' => $ln['qty'],
            'total'    => $ln['line_total'],
        ]);

        return view('frontend.success', [
            'successMessage' => 'Order placed successfully!',
            'order'          => $order,
            'cartItems'      => $cartItemsForView,
            'subtotal'       => $subtotal,
            'total'          => $total,
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
