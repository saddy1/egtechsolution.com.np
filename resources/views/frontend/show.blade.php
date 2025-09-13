@extends('frontend.layouts.app')
@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-8">
                <h2 class="text-4xl font-bold bg-gradient-to-r from-red-600 to-pink-600 bg-clip-text text-transparent">
                    Product Detail
                </h2>
                <div class="w-24 h-1 bg-gradient-to-r from-red-500 to-pink-500 mx-auto mt-4 rounded-full"></div>
            </div>

            <!-- Main Product Section -->
            <div class="mb-12">
                <div
                    class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden backdrop-blur-sm bg-white/80">
                    <div class="p-6 lg:p-8">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                            <!-- Product Image -->
                            <div class="order-2 lg:order-1">
                                <div class="sticky top-8">
                                    <div class="relative group">
                                        <div
                                            class="absolute inset-0 bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl opacity-20 blur-xl group-hover:opacity-30 transition-opacity duration-300">
                                        </div>
                                        <div class="relative bg-white rounded-2xl p-4 shadow-lg">
                                            <img src="{{ asset('assets/product/' . $product->name . '.png') }}"
                                                class="w-full h-auto rounded-xl object-cover transition-transform duration-500 group-hover:scale-105"
                                                style="max-height: 500px;" alt="{{ $product->name }}" loading="lazy">
                                            <div
                                                class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Details -->
                            <div class="order-1 lg:order-2 space-y-6">
                                <!-- Activity Stats -->
                                <div class="bg-gray-50 rounded-xl p-6">
                                    <h6 class="text-lg font-semibold text-gray-900 mb-4">Share this product</h6>
                                    <div class="flex flex-wrap gap-3">
                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                            target="_blank" rel="noopener noreferrer"
                                            class="inline-flex items-center justify-center w-12 h-12 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg transform transition-all duration-200 hover:scale-110">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                            </svg>
                                        </a>
                                        <a href="whatsapp://send?text={{ urlencode(url()->current()) }}"
                                            class="inline-flex items-center justify-center w-12 h-12 bg-green-500 hover:bg-green-600 text-white rounded-xl shadow-lg transform transition-all duration-200 hover:scale-110">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488" />
                                            </svg>
                                        </a>
                                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}"
                                            target="_blank" rel="noopener noreferrer"
                                            class="inline-flex items-center justify-center w-12 h-12 bg-sky-500 hover:bg-sky-600 text-white rounded-xl shadow-lg transform transition-all duration-200 hover:scale-110">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                            </svg>
                                        </a>
                                        <a href="#"
                                            class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white rounded-xl shadow-lg transform transition-all duration-200 hover:scale-110">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.746-1.378l-.747 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.624 0 11.99-5.367 11.99-11.987C24.007 5.367 18.641.001.017.001z" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>

                                <!-- Product Name -->
                                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight">{{ $product->name }}
                                </h1>

                                <!-- Labels -->
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gradient-to-r from-emerald-500 to-green-500 text-white shadow-lg">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        #1 Best seller for Electronics Items
                                    </span>
                                    <span
                                        class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gradient-to-r from-amber-500 to-yellow-500 text-white shadow-lg">
                                        In EG TECH SOLUTION
                                    </span>
                                </div>

                                <!-- Price -->
                                <div
                                    class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-6 border border-blue-200">
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                                        <div
                                            class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                            Rs.{{ $product->rate }}
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <span class="text-xl text-gray-500 line-through">
                                                Rs.{{ $product->rate + $product->discount }}
                                            </span>
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-gradient-to-r from-red-500 to-pink-500 text-white shadow-lg animate-pulse">
                                                {{ number_format(($product->discount / $product->rate) * 100, 0) }}% OFF
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                @if ($product->quantity > 0)
                                    @php
                                        $maxPerOrder = min(5, (int) $product->quantity); // hard cap by stock
                                    @endphp

                                    <!-- Quantity Selector -->
                                    <div class="space-y-4">
                                        <h6 class="text-lg font-semibold text-gray-900">Quantity</h6>
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="flex items-center border-2 border-gray-200 rounded-xl overflow-hidden bg-white shadow-sm">
                                                <button
                                                    class="px-4 py-3 bg-gray-50 hover:bg-gray-100 text-gray-600 hover:text-gray-900 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                                    type="button" onclick="updateQuantity(-1)" id="decrease-btn">-</button>

                                                <input type="text" id="quantity"
                                                    class="w-16 py-3 text-center border-0 focus:ring-0 font-semibold text-lg"
                                                    value="1" min="1" max="{{ $maxPerOrder }}" readonly>

                                                <button
                                                    class="px-4 py-3 bg-gray-50 hover:bg-gray-100 text-gray-600 hover:text-gray-900 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                                    type="button" onclick="updateQuantity(1)" id="increase-btn">+</button>
                                            </div>
                                            <span class="text-sm text-gray-500">
                                                In stock: <b>{{ $product->quantity }}</b> • Max per order:
                                                <b>{{ $maxPerOrder }}</b>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Add to Cart -->
                                    <form action="{{ route('cart.add') }}" method="POST" class="w-full">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" id="form-quantity" value="1">
                                        <button type="submit"
                                            class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg transform transition-all duration-200 hover:scale-105 hover:shadow-xl flex items-center justify-center gap-3"
                                            {{ $product->quantity < 1 ? 'disabled' : '' }}>
                                            Add To Cart
                                        </button>
                                    </form>
                                @else
                                    <div class="bg-red-50 border border-red-200 rounded-xl p-6 text-center">
                                        <div class="text-2xl font-bold text-red-600 mb-2">Out of Stock</div>
                                        <div class="text-red-500">This item is currently unavailable</div>
                                    </div>
                                @endif

                                @if (session('success'))
                                    <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center justify-between"
                                        role="alert">
                                        <div class="flex items-center">
                                            <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="text-green-800 font-medium">{{ session('success') }}</span>
                                        </div>
                                        <button type="button" class="text-green-600 hover:text-green-800"
                                            data-bs-dismiss="alert">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endif



                                <!-- Share Section -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Specifications Section -->
            <!-- Specifications Table -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6">
                    <h3 class="text-2xl font-bold text-center">Product Specifications</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Specification</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Value</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($product->specifications as $spec)
                                <tr class="hover:bg-blue-50 transition-colors duration-200">
                                    <td class="px-6 py-4 text-gray-900 font-semibold">{{ $spec->name }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $spec->value }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-4 text-center text-gray-500">No specifications
                                        available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Quantity Management
        function updateQuantity(change) {
            const quantityInput = document.getElementById('quantity');
            const formQuantityInput = document.getElementById('form-quantity');
            const decreaseBtn = document.getElementById('decrease-btn');
            const increaseBtn = document.getElementById('increase-btn');

            let currentQuantity = parseInt(quantityInput.value);
            let newQuantity = currentQuantity + change;

            // Ensure quantity stays within bounds
            if (newQuantity < 1) newQuantity = 1;
            if (newQuantity > 5) newQuantity = 5;

            // Update both inputs
            quantityInput.value = newQuantity;
            formQuantityInput.value = newQuantity;

            // Update button states
            decreaseBtn.disabled = newQuantity <= 1;
            increaseBtn.disabled = newQuantity >= 5;
        }

        // Image Gallery and Zoom Functionality
    </script>
    <script>
  // Quantity Management (respects stock cap)
  const MAX_QTY = {{ $maxPerOrder }}; // min(5, stock)

  function updateQuantity(change) {
    const quantityInput = document.getElementById('quantity');
    const formQuantityInput = document.getElementById('form-quantity');
    const decreaseBtn = document.getElementById('decrease-btn');
    const increaseBtn = document.getElementById('increase-btn');

    let q = parseInt(quantityInput.value || '1', 10);
    q = Math.min(MAX_QTY, Math.max(1, q + change));

    quantityInput.value = q;
    formQuantityInput.value = q;

    decreaseBtn.disabled = q <= 1;
    increaseBtn.disabled = q >= MAX_QTY;
  }

  // initialize button states
  document.addEventListener('DOMContentLoaded', () => updateQuantity(0));
</script>

@endsection
