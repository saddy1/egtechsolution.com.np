@extends('frontend.layouts.app')

@push('head')
    <title>Shop / Search Result</title>
@endpush

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <h3 class="text-2xl font-semibold mb-2">Shop</h3>
        <nav class="text-gray-500 text-sm" aria-label="breadcrumb">
            <ol class="flex space-x-2">
                <li>
                    <a href="{{ url('/') }}" class="hover:text-gray-700"><i class="fas fa-home"></i></a>
                    <span>/</span>
                </li>
                <li class="text-gray-700">Shop</li>
            </ol>
        </nav>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Sidebar Filters -->
        <aside class="w-full lg:w-1/4 bg-white p-4 rounded shadow space-y-4">
            <!-- Brand Filter -->
            <div>
                <h4 class="font-semibold mb-2">Brand</h4>
                <ul class="space-y-1 max-h-48 overflow-y-auto">
                    @foreach($brand as $br)
                    <li>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" value="{{ $br }}" class="form-checkbox h-4 w-4 text-red-600">
                            <span>{{ $br }} ({{ \App\Models\Product::where('brand', $br)->count() }})</span>
                        </label>
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- Price Filter -->
            <div>
                <h4 class="font-semibold mb-2">Price</h4>
                <input type="range" min="0" max="1000" class="w-full">
            </div>

            <!-- Category Filter -->
            <div>
                <h4 class="font-semibold mb-2">Category</h4>
                <ul class="space-y-1 max-h-48 overflow-y-auto">
                    @foreach($category as $cat)
                    <li>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" value="{{ $cat }}" class="form-checkbox h-4 w-4 text-red-600">
                            <span>{{ $cat }} ({{ \App\Models\Product::where('category', $cat)->count() }})</span>
                        </label>
                    </li>
                    @endforeach
                </ul>
            </div>
        </aside>

        <!-- Product Grid -->
        <main class="w-full lg:w-3/4">
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($products->take(16) as $row)
                <div class="bg-white rounded shadow overflow-hidden relative group">
                    <!-- Image + Discount Badge -->
                    <a href="{{ url('/products/' . str_replace(' ', '-', $row->name) . '/' . $row->id) }}">
                        <img src="{{ asset('assets/product/' . $row->name . '.png') }}" alt="{{ $row->name }}" class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                        <span class="absolute top-2 left-2 bg-red-600 text-white text-xs px-2 py-1 rounded">
                            {{ number_format(($row->discount / $row->rate) * 100, 0) }}% Off
                        </span>
                    </a>

                    <!-- Product Info -->
                    <div class="p-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-red-600 font-semibold">${{ $row->rate }}</span>
                            <ul class="flex space-x-1">
                                @for($i = 0; $i < 5; $i++)
                                <li>
                                    <i class="fas fa-star {{ $i < 2 ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                </li>
                                @endfor
                            </ul>
                        </div>
                        <a href="{{ url('/products/' . str_replace(' ', '-', $row->name) . '/' . $row->id) }}" class="block text-gray-700 hover:text-red-600 font-medium truncate">
                            {{ $row->name }}
                        </a>
                    </div>

                    <!-- Action Buttons -->
                    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30 opacity-0 group-hover:opacity-100 transition-opacity">
                        <div class="flex space-x-2">
                            <button class="bg-white p-2 rounded hover:bg-gray-100"><i class="fas fa-shopping-cart text-red-600"></i></button>
                            <button class="bg-white p-2 rounded hover:bg-gray-100"><i class="fas fa-eye text-gray-600"></i></button>
                            <button class="bg-white p-2 rounded hover:bg-gray-100"><i class="fas fa-heart text-red-600"></i></button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
           
        </main>
    </div>
</div>
@endsection
