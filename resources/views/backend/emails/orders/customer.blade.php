@component('mail::message')
# Thank you for your order!

Hi {{ $order->name }},  
We’ve received your order **#{{ $order->id }}**.

**Placed at:** {{ ($order->placed_at ?? $order->created_at)->format('d M Y, h:i A') }}  
**Total:** Rs. {{ number_format($order->total, 2) }}  
**Payment:** {{ strtoupper($order->payment_method) }}  
**Status:** {{ ucfirst($order->order_status) }}

## Delivery Address
{{ $order->address }}  
{{ $order->city }}, {{ $order->country }} {{ $order->zip }}

## Items
@component('mail::table')
| Product | Qty | Rate (Rs.) | Total (Rs.) |
|:--|:--:|--:|--:|
@foreach($order->items as $item)
| {{ $item->product_name }} | {{ $item->quantity }} | {{ number_format($item->price, 2) }} | {{ number_format($item->total, 2) }} |
@endforeach
@endcomponent

@component('mail::button', ['url' => url('/')])
Continue Shopping
@endcomponent

Thanks,<br>
EG Tech Solution
@endcomponent
