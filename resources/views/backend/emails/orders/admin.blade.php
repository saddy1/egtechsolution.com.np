@component('mail::message')
# New Order Received

**Order:** #{{ $order->id }}  
**Placed at:** {{ ($order->placed_at ?? $order->created_at)->format('d M Y, h:i A') }}

## Customer
- **Name:** {{ $order->name }}
- **Email:** {{ $order->email }}
- **Phone:** {{ $order->phone }}

## Delivery Address
{{ $order->address }}  
{{ $order->city }}, {{ $order->country }} {{ $order->zip }}

## Payment
- **Method:** {{ strtoupper($order->payment_method) }}
- **Total:** Rs. {{ number_format($order->total, 2) }}
- **Status:** {{ ucfirst($order->order_status) }}

## Items
@component('mail::table')
| Product | Qty | Rate (Rs.) | Total (Rs.) |
|:--|:--:|--:|--:|
@foreach($order->items as $item)
| {{ $item->product_name }} | {{ $item->quantity }} | {{ number_format($item->price, 2) }} | {{ number_format($item->total, 2) }} |
@endforeach
@endcomponent

@component('mail::button', ['url' => route('admin.orders.show', $order->id)])
View Order in Admin
@endcomponent

Thanks,<br>
EG Tech Solution
@endcomponent
