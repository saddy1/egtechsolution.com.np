<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\OrderPlacedAdmin;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

/**
 * API used by broadtechinfosys.com.np to list our products and to send us
 * the orders its customers place for them. This site stays the stock master.
 */
class PartnerController extends Controller
{
    public function products(Request $request)
    {
        $products = Product::with(['category', 'brand', 'specifications', 'images'])
            ->where('is_active', true)
            ->orderBy('id')
            ->paginate(min((int) $request->get('per_page', 100), 200));

        return response()->json([
            'data' => $products->getCollection()->map(fn (Product $p) => [
                'id'             => $p->id,
                'name'           => $p->name,
                'sku'            => $p->sku,
                'description'    => $p->description,
                'price'          => (float) $p->final_price,
                'regular_price'  => (float) $p->price,
                'quantity'       => $p->stock_status === 'in_stock' ? max(0, (int) $p->quantity) : 0,
                'category'       => $p->category?->name,
                'brand'          => $p->brand?->name,
                'image'          => $p->image ? asset($p->image) : null,
                'images'         => array_map(fn ($path) => asset($path), $p->gallery()),
                'specifications' => $p->specifications->pluck('value', 'name'),
                'is_featured'    => $p->is_featured,
                'variant_group'  => $p->variant_group_id,
                'variant_options' => $p->variant_options,
                'url'            => url('/products/'.$p->slug.'/'.$p->id),
                'updated_at'     => $p->updated_at?->toAtomString(),
            ]),
            'meta' => ['current_page' => $products->currentPage(), 'last_page' => $products->lastPage(), 'total' => $products->total()],
        ]);
    }

    public function storeOrder(Request $request)
    {
        $data = $request->validate([
            'reference'          => 'required|string|max:60',
            'customer_name'      => 'required|string|max:255',
            'phone'              => 'required|string|max:40',
            'email'              => 'nullable|email|max:150',
            'address'            => 'required|string|max:2000',
            'city'               => 'required|string|max:255',
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        // The partner may retry after a timeout: the same reference never creates a second order.
        if ($existing = Order::where('external_reference', $data['reference'])->first()) {
            return $this->orderResponse($existing, 200);
        }

        $order = DB::transaction(function () use ($data) {
            $partner = User::firstOrCreate(
                ['email' => 'orders@broadtechinfosys.com.np'],
                ['name' => 'Broad Tech Infosys (partner orders)']
            );

            $lines    = [];
            $subtotal = 0;

            foreach ($data['items'] as $item) {
                $product = Product::lockForUpdate()->find($item['product_id']);

                if (! $product || ! $product->is_active) {
                    throw ValidationException::withMessages(['items' => "Product {$item['product_id']} is not available."]);
                }
                if ($product->stock_status !== 'in_stock' || $product->quantity < $item['quantity']) {
                    throw ValidationException::withMessages(['items' => "Only {$product->quantity} left in stock for {$product->name}."]);
                }

                $lineTotal = $product->final_price * $item['quantity'];
                $subtotal += $lineTotal;
                $lines[]   = [
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'quantity'     => $item['quantity'],
                    'price'        => $product->final_price,
                    'total'        => $lineTotal,
                    'status'       => OrderItem::STATUS_PENDING,
                ];

                $product->quantity -= $item['quantity'];
                if ($product->quantity <= 0) {
                    $product->stock_status = 'out_of_stock';
                }
                $product->save();
            }

            $order = Order::create([
                'user_id'            => $partner->id,
                'name'               => $data['customer_name'],
                'phone'              => $data['phone'],
                'email'              => $data['email'] ?? null,
                'address'            => $data['address'],
                'city'               => $data['city'],
                'country'            => 'Nepal',
                'subtotal'           => $subtotal,
                'total'              => $subtotal,
                'payment_method'     => 'cod',
                'order_status'       => Order::STATUS_PENDING,
                'placed_at'          => now(),
                'source'             => 'broadtech',
                'external_reference' => $data['reference'],
            ]);
            $order->items()->createMany($lines);

            return $order;
        });

        try {
            Mail::to('egtechsolution1@gmail.com')->send(new OrderPlacedAdmin($order->load(['items', 'user'])));
        } catch (\Throwable $e) {
            Log::error('Admin email send failed', ['order_id' => $order->id, 'msg' => $e->getMessage()]);
        }

        return $this->orderResponse($order, 201);
    }

    public function showOrder(string $reference)
    {
        return $this->orderResponse(Order::where('external_reference', $reference)->firstOrFail(), 200);
    }

    private function orderResponse(Order $order, int $status)
    {
        return response()->json([
            'id'        => $order->id,
            'reference' => $order->external_reference,
            'status'    => $order->order_status,
            'total'     => (float) $order->total,
        ], $status);
    }
}
