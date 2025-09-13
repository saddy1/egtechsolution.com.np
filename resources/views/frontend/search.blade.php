@extends('frontend.layouts.app')

@push('head')
    <title>Search Result</title>
@endpush

@section('content')
<section class="overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Title -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-semibold">Search Result</h2>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 gap-4 sm:gap-6">
            @foreach ($products->take(16) as $row)
                <div class="bg-white shadow rounded-lg overflow-hidden hover:shadow-lg transition duration-300">
                    <div class="relative">
                        <a href="{{ url('/products/' . str_replace(' ', '-', $row->name) . '/' . $row->id) }}">
                            <img src="{{ asset('assets/product/' . $row->name . '.png') }}" 
                                 alt="{{ $row->name }}" 
                                 class="w-full h-48 object-cover blur-up lazyload">
                        </a>

                        <!-- Discount Label -->
                        <div class="absolute top-2 left-2 bg-red-600 text-white text-xs font-semibold px-2 py-1 rounded">
                            {{ number_format(($row->discount / $row->rate) * 100, 0) }}% Off
                        </div>

                        <!-- Cart / Wishlist / View Icons -->
                        <div class="absolute bottom-2 right-2 flex space-x-2">
                            <button class="bg-white p-2 rounded shadow hover:bg-gray-100 addtocart-btn">
                                <i data-feather="shopping-cart" class="w-4 h-4"></i>
                            </button>
                            <button class="bg-white p-2 rounded shadow hover:bg-gray-100">
                                <i data-feather="eye" class="w-4 h-4"></i>
                            </button>
                            <button class="bg-white p-2 rounded shadow hover:bg-gray-100 wishlist">
                                <i data-feather="heart" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-green-600 font-semibold">${{ $row->rate }}</span>
                            <ul class="flex space-x-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <li>
                                        <i class="fas fa-star {{ $i <= 2 ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    </li>
                                @endfor
                            </ul>
                        </div>

                        <a href="{{ url('/products/' . str_replace(' ', '-', $row->name) . '/' . $row->id) }}" class="block">
                            <h5 class="text-gray-800 font-medium truncate">{{ Str::limit($row->name, 85, '...') }}</h5>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8 flex justify-center">
            {{ $products->appends(['search' => $search])->links('pagination::tailwind') }}
        </div>
    </div>
</section>
@endsection
