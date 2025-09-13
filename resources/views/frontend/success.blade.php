@extends('frontend.layouts.app')
@section('content')

<!-- Success Section -->
<section class="pt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-green-600 mx-auto mb-6 relative">
                <svg viewBox="0 0 65 51" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-white">
                    <path d="M7 25L27.3077 44L58.5 7" stroke="white" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>

            <h4 class="text-2xl font-semibold text-gray-800 mb-2">Order Success</h4>
            <h5 class="text-gray-600 mb-1">{{ $successMessage }}</h5>
            <h6 class="text-gray-500">Transaction ID: <span class="font-medium">{{ $order->id }}</span></h6>
        </div>
    </div>
</section>

<!-- Order Details Section -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold mb-8">Order Details</h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Order Items Table -->
            <div class="bg-white p-6 rounded-lg shadow overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($order->items as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="py-4 px-2">
                                    <img src="{{ asset('path_to_image/' . $item->product_id . '.jpg') }}" alt="{{ $item->product_name }}" class="w-20 h-20 object-cover rounded">
                                </td>
                                <td class="py-4 px-4">
                                    <p class="text-gray-500 text-sm">Product Name</p>
                                    <h5 class="text-gray-800 font-medium">{{ $item->product_name }}</h5>
                                </td>
                                <td class="py-4 px-4">
                                    <p class="text-gray-500 text-sm">Quantity</p>
                                    <h5 class="text-gray-800">{{ $item->quantity }}</h5>
                                </td>
                                <td class="py-4 px-4">
                                    <p class="text-gray-500 text-sm">Price</p>
                                    <h5 class="text-gray-800">${{ number_format($item->price, 2) }}</h5>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr class="font-medium">
                            <td colspan="3" class="py-3 px-4 text-right text-gray-700">Subtotal :</td>
                            <td class="py-3 px-4 text-gray-800">${{ number_format($order->items->sum('total'), 2) }}</td>
                        </tr>
                        <tr class="font-medium">
                            <td colspan="3" class="py-3 px-4 text-right text-gray-700">Shipping :</td>
                            <td class="py-3 px-4 text-gray-800">$12.00</td>
                        </tr>
                        <tr class="font-medium">
                            <td colspan="3" class="py-3 px-4 text-right text-gray-700">Tax (GST) :</td>
                            <td class="py-3 px-4 text-gray-800">$10.00</td>
                        </tr>
                        <tr class="font-bold text-lg text-green-600">
                            <td colspan="3" class="py-3 px-4 text-right">Total Price :</td>
                            <td class="py-3 px-4">${{ number_format($order->total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Order Summary & Shipping -->
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h4 class="text-xl font-semibold mb-4">Summary</h4>
                    <ul class="text-gray-700 space-y-2">
                        <li>Order ID: {{ $order->id }}</li>
                        <li>Order Date: {{ $order->created_at->format('F j, Y') }}</li>
                        <li>Order Total: ${{ number_format($order->total, 2) }}</li>
                    </ul>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <h4 class="text-xl font-semibold mb-4">Shipping Address</h4>
                    <ul class="text-gray-700 space-y-2">
                        <li>{{ $order->name }}</li>
                        <li>{{ $order->address }}</li>
                        <li>{{ $order->city }}, {{ $order->country }} {{ $order->zip }}</li>
                        <li>Contact No: {{ $order->phone }}</li>
                    </ul>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <h4 class="text-xl font-semibold mb-2">Payment Method</h4>
                    <p class="text-gray-600 text-sm">Pay on Delivery (Cash/Card). Cash on delivery (COD) available. Card/Net banking acceptance subject to device availability.</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow text-gray-800">
                    <h3 class="text-lg font-semibold">Expected Date of Delivery: 
                        <span class="font-medium">{{ now()->addDays(5)->format('F j, Y') }}</span>
                    </h3>
                    {{-- <a href="{{ route('order.tracking', ['order' => $order->id]) }}" class="text-blue-600 hover:underline">Track Order</a> --}}
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
