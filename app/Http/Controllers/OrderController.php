<?php
namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    // Display orders grouped by client
    public function index()
    {
        $orders = Order::with('items')
        ->orderBy('created_at','desc')
        ->take(8)
        ->get();
        $ordersGroupedByClient = $orders->groupBy(function ($order) {
            return $order->name; // Assuming 'name' is the client name field
        });

        return view('backend.orders.orders', compact('ordersGroupedByClient'));
    }

 
    public function verify(OrderItem $orderItem)
    {
        $orderItem->update(['status' => 'Verified']);
        return redirect()->route('admin.orders')->with('status', 'Order item verified!');
    }

    public function ship(OrderItem $orderItem)
    {
        $orderItem->update(['status' => 'Shipped']);
        return redirect()->route('admin.orders')->with('status', 'Order item shipped!');
    }

    public function deliver(OrderItem $orderItem)
    {
        $orderItem->update(['status' => 'Delivered']);
        return redirect()->route('admin.orders')->with('status', 'Order item delivered!');
    }
    // OrderController.php
public function destroy(Order $order)
{
    // Delete associated order items
    $order->items()->delete();

    // Delete the order itself
    $order->delete();

    return redirect()->back()->with('status', 'Order deleted successfully!');
}

}