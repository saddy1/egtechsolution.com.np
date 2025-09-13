@extends('frontend.layouts.app')
@push('head')
    <title>EG Tech Solution</title>
@endpush

@section('content')
    @php
        function formatNepaliCurrency($num)
        {
            $num = (string) $num;
            $last3 = substr($num, -3);
            $rest = substr($num, 0, -3);
            if ($rest != '') {
                $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest);
                return $rest . ',' . $last3;
            } else {
                return $last3;
            }
        }
    @endphp

    {{-- Banner Section Start --}}

    <div id="banner-carousel" class="relative w-full" data-carousel="slide" data-autoplay="true" data-interval="6000">
        <!-- Carousel wrapper -->
        <div class="relative h-64 sm:h-80 md:h-[80vh] lg:h-[100vh] overflow-hidden">
            <!-- Item 1 -->
            <div class="hidden duration-700 ease-in-out" data-carousel-item>
                <img src="{{ asset('assets/banner5.jpg') }}"
                    class="absolute block w-full h-full object-contain md:object-cover top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-black"
                    alt="Banner 1">
            </div>
            <!-- Item 2 -->
            <div class="hidden duration-700 ease-in-out" data-carousel-item>
                <img src="{{ asset('assets/banner2.jpg') }}"
                    class="absolute block w-full h-full object-contain md:object-cover top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-black"
                    alt="Banner 2">
            </div>
            <!-- Item 3 -->
            <div class="hidden duration-700 ease-in-out" data-carousel-item>
                <img src="{{ asset('assets/banner3.jpg') }}"
                    class="absolute block w-full h-full object-contain md:object-cover top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-black"
                    alt="Banner 3">
            </div>
        </div>

        <!-- Indicators -->
        <div class="absolute z-30 flex -translate-x-1/2 bottom-4 left-1/2 space-x-2">
            <button type="button" class="w-3 h-3 rounded-full bg-white/50" aria-current="true" aria-label="Slide 1"
                data-carousel-slide-to="0"></button>
            <button type="button" class="w-3 h-3 rounded-full bg-white/30" aria-current="false" aria-label="Slide 2"
                data-carousel-slide-to="1"></button>
            <button type="button" class="w-3 h-3 rounded-full bg-white/30" aria-current="false" aria-label="Slide 3"
                data-carousel-slide-to="2"></button>
        </div>

        <!-- Controls -->
        <button type="button"
            class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
            data-carousel-prev>
            <span
                class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 group-focus:ring-4 group-focus:ring-white">
                <svg class="w-4 h-4 text-white" viewBox="0 0 6 10" fill="none">
                    <path d="M5 1 1 5l4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                <span class="sr-only">Previous</span>
            </span>
        </button>
        <button type="button"
            class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
            data-carousel-next>
            <span
                class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 group-focus:ring-4 group-focus:ring-white">
                <svg class="w-4 h-4 text-white" viewBox="0 0 6 10" fill="none">
                    <path d="m1 9 4-4-4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                <span class="sr-only">Next</span>
            </span>
        </button>
    </div>


    {{-- Banner Section End --> --}}



    {{-- Product Section --}}
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold">New Arrival</h2>
                <h5 class="text-green-600">Our Collection</h5>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-6">
                @foreach ($products->take(16) as $row)
                    <div class="bg-white p-4 rounded-xl shadow hover:shadow-lg transition">
                        <a href="{{ url('/products/' . str_replace(' ', '-', $row->name) . '/' . $row->id) }}">
                            <img src="{{ asset('assets/product/' . $row->name . '.png') }}" alt="{{ $row->name }}"
                                class="w-full h-40 object-contain mb-3">
                        </a>

                        <div class="flex justify-between items-center mb-2">



                            <span class="text-green-600 font-bold">Rs.{{ formatNepaliCurrency($row->rate) }}</span>

                            <span class="bg-red-500 text-white text-xs px-2 py-1 rounded">
                                {{ number_format(($row->discount / $row->rate) * 100, 0) }}% Off
                            </span>
                        </div>

                        <a href="{{ url('/products/' . str_replace(' ', '-', $row->name) . '/' . $row->id) }}">
                            <h5 class="text-sm font-semibold text-gray-800">
                                {{ Str::limit($row->name, 40, '...') }}
                            </h5>
                        </a>

                        {{-- Rating --}}
                        <div class="flex mt-2 text-yellow-400 text-sm">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star text-gray-300"></i>
                            <i class="fas fa-star text-gray-300"></i>
                            <i class="fas fa-star text-gray-300"></i>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
