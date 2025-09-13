@extends('backend.layout.app')

@section('content')
<div class="container">
    <h3>Orders</h3>
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    @foreach ($ordersGroupedByClient as $clientName => $orders)
        <h4>Client: {{ $clientName }}</h4>
        @foreach ($orders as $order)
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Total Cost</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>${{ number_format($item->total, 2) }}</td>
                            <td>{{ $item->status }}</td>
                            <td>
                                @if ($item->status == 'Pending')
                                    <form action="{{ route('admin.orders.verify', $item) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">Verify</button>
                                    </form>
                                @elseif ($item->status == 'Verified')
                                    <form action="{{ route('admin.orders.ship', $item) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-warning">Ship</button>
                                    </form>
                                @elseif ($item->status == 'Shipped')
                                    <form action="{{ route('admin.orders.deliver', $item) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success">Deliver</button>
                                    </form>
                                @endif

                                 
                                <form action="{{ route('admin.orders.delete', $order) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this order?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <hr>
        @endforeach
    @endforeach
</div>
@endsection
