@extends('frontend.layouts.app')
@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <h3 class="text-2xl font-semibold text-gray-800 mb-2">Checkout</h3>
        <nav class="text-gray-500 text-sm" aria-label="Breadcrumb">
            <ol class="inline-flex space-x-2">
                <li>
                    <a href="/" class="hover:text-gray-700">
                        <i class="fas fa-home"></i>
                    </a>
                </li>
                <li>/</li>
                <li class="text-gray-700">Checkout</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Checkout Section -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Billing Form -->
            <div class="lg:col-span-8 bg-white p-6 rounded-lg shadow">
                <form class="space-y-6" method="POST" action="{{ route('place.order') }}">
                    @csrf
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Billing / Shipping Address</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="name" class="block text-gray-700 font-medium">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                placeholder="Enter Full Name"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                            @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-gray-700 font-medium">Phone</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                placeholder="Enter Phone Number"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror">
                            @error('phone')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="email" class="block text-gray-700 font-medium">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                placeholder="Email"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                            @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-3">
                            <label for="address" class="block text-gray-700 font-medium">Address</label>
                            <textarea name="address" id="address"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-500 @enderror"
                                placeholder="Enter Address">{{ old('address') }}</textarea>
                            @error('address')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="city" class="block text-gray-700 font-medium">City</label>
                            <input type="text" name="city" id="city" value="{{ old('city') }}" placeholder="City"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('city') border-red-500 @enderror">
                            @error('city')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="country" class="block text-gray-700 font-medium">Country</label>
                            <input type="text" name="country" id="country" value="{{ old('country') }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('country') border-red-500 @enderror">
                            @error('country')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="zip" class="block text-gray-700 font-medium">Zip</label>
                            <input type="text" name="zip" id="zip" value="{{ old('zip') }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('zip') border-red-500 @enderror">
                            @error('zip')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <hr class="my-6 border-gray-300">

                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Payment Method</h3>
                    <div class="space-y-3">
                        <label class="inline-flex items-center">
                            <input type="radio" name="payment_method" value="cod"
                                class="form-radio text-blue-600" {{ old('payment_method', 'cod') == 'cod' ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-700">Cash On Delivery</span>
                        </label>
                    </div>

                    <button type="submit"
                        class="mt-6 w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Place
                        Order</button>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-4">
                <div class="bg-white p-6 rounded-lg shadow space-y-4">
                    <h3 class="text-xl font-semibold flex justify-between items-center">
                        Your Cart
                        <span
                            class="bg-gray-800 text-white rounded-full px-3 py-1 text-sm">{{ isset($cartItems) ? count($cartItems) : 0 }}</span>
                    </h3>

                    <ul class="divide-y divide-gray-200">
                        @foreach ($cartItems as $item)
                            <li class="flex justify-between py-2">
                                <div>
                                    <p class="text-gray-800 font-medium">{{ $item['name'] }}</p>
                                    <p class="text-gray-500 text-sm">Quantity: {{ $item['quantity'] }}</p>
                                </div>
                                <span class="text-gray-800">Rs.{{ $item['total'] }}</span>
                            </li>
                        @endforeach
                        <li class="flex justify-between py-2 font-semibold border-t border-gray-200">
                            <span>Subtotal</span>
                            <span>Rs.{{ $subtotal  }}</span>
                        </li>
                        <li class="flex justify-between py-2 font-bold">
                            <span>Total (Rs.)</span>
                            <span>Rs.{{ $total }}</span>
                        </li>
                    </ul>

                    <form class="mt-4">
                        <input type="text" placeholder="Promo code"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
