<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF; // only if you install the package
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /** Latest orders grouped by customer name (or user_id if you prefer) */
    public function index(Request $request)
{
    $q      = $request->input('q');
    $status = $request->input('status');

    $orders = Order::query()
        ->withCount('items')
        ->when($q, function ($qry) use ($q) {
            $qry->where(function ($w) use ($q) {
                $w->where('id', $q)
                  ->orWhere('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
                  ->orWhere('phone', 'like', "%{$q}%");
            });
        })
        ->when($status, fn($qry) => $qry->where('order_status', $status))
        ->orderByDesc('created_at')
        ->paginate(12)
        ->withQueryString();

    return view('backend.orders.index', compact('orders', 'q', 'status'));
}
    public function show(Order $order)
{
    // eager load items + optional product info
    $order->load(['items.product', 'user']);

    return view('backend.orders.show', compact('order'));
}

    public function verify(OrderItem $orderItem)
    {
        $orderItem->update(['status' => OrderItem::STATUS_VERIFIED]);
        $orderItem->order->refreshStatus();
        return back()->with('status', 'Order item verified!');
    }

    public function ship(OrderItem $orderItem)
    {
        $orderItem->update(['status' => OrderItem::STATUS_SHIPPED]);
        $orderItem->order->refreshStatus();
        return back()->with('status', 'Order item shipped!');
    }

    public function deliver(OrderItem $orderItem)
    {
        $orderItem->update(['status' => OrderItem::STATUS_DELIVERED]);
        $orderItem->order->refreshStatus();
        return back()->with('status', 'Order item delivered!');
    }

    public function cancelItem(OrderItem $orderItem)
    {
        $orderItem->update(['status' => OrderItem::STATUS_CANCELLED]);
        $orderItem->order->refreshStatus();
        return back()->with('status', 'Order item cancelled!');
    }

public function destroy(Order $order)
{
    $order->load('items.product');

    DB::transaction(function () use ($order) {
        foreach ($order->items as $item) {
            if ($item->product) {
                $p = $item->product()->lockForUpdate()->first();
                $p->quantity += $item->quantity;
                if (property_exists($p, 'stock_status') && $p->quantity > 0) {
                    $p->stock_status = 'in_stock';
                }
                $p->save();
            }
        }
        $order->items()->delete();
        $order->delete();
    });

    return back()->with('status', 'Order deleted and stock restored.');
}




public function invoiceHtml(Order $order)
{
    $order->load(['items.product','user']);
    return view('backend.orders.invoice', compact('order'));
}

public function invoicePdf(Order $order)
{
    // REQUIREMENT: composer require barryvdh/laravel-dompdf
    $order->load(['items.product','user']);
    $pdf = PDF::loadView('backend.orders.invoice', compact('order'))->setPaper('a4');
    $file = 'invoice-'.now()->format('Ymd').'-'.$order->id.'.pdf';
    return $pdf->download($file);
}


}
