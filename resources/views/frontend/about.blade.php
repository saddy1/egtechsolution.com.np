@extends('frontend.layouts.app')

@push('head')
<title>About • EG Tech Solution</title>
@endpush

@section('content')
<section class="relative">
  {{-- Hero --}}
  <div class="relative overflow-hidden bg-gradient-to-br from-indigo-50 via-white to-emerald-50">
    <div class="absolute -top-32 -right-24 h-80 w-80 rounded-full bg-indigo-200/30 blur-3xl"></div>
    <div class="absolute -bottom-20 -left-24 h-96 w-96 rounded-full bg-emerald-200/30 blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
        <div>
          <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700 ring-1 ring-indigo-200">Since 2023</span>
          <h1 class="mt-4 text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900">
            We build tech that people love to use.
          </h1>
          <p class="mt-3 text-slate-600">
            EG Tech Solution is a Nepal-based company delivering quality laptops, computers, tablets, accessories and support.
            We obsess over performance, price–value, and friendly service.
          </p>
          <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-white font-semibold shadow hover:bg-indigo-700">
              Contact Us
            </a>
            <a href="{{ url('/shop') }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-2.5 font-semibold text-slate-800 ring-1 ring-slate-200 hover:bg-slate-50">
              Explore Store
            </a>
          </div>
        </div>

        {{-- Hero image (replace with your own asset) --}}
        <div class="relative">
          <img
            src="{{ asset('assets/egtech_shop.jpg') }}"
            onerror="this.src='https://images.unsplash.com/photo-1518779578993-ec3579fee39f?q=80&w=1600&auto=format&fit=crop';"
            alt="EG Tech Solution team and devices"
            class="w-full h-[360px] sm:h-[420px] lg:h-[520px] object-cover rounded-3xl shadow-xl ring-1 ring-slate-200">
          {{-- Floating badge --}}
          <div class="absolute -bottom-4 -left-4 bg-white/90 backdrop-blur rounded-2xl p-4 shadow-lg ring-1 ring-slate-200">
            <p class="text-xs text-slate-500">Customer Satisfaction</p>
            <p class="text-xl font-extrabold text-emerald-600">4/5</p>
          </div>
        </div>
      </div>

      {{-- Stats --}}
      <div class="mt-12 grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
          <div class="text-2xl font-extrabold text-slate-900">10k+</div>
          <div class="text-xs text-slate-500">Products Shipped</div>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
          <div class="text-2xl font-extrabold text-slate-900">98%</div>
          <div class="text-xs text-slate-500">On-time Delivery</div>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
          <div class="text-2xl font-extrabold text-slate-900">50+</div>
          <div class="text-xs text-slate-500">Brands</div>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
          <div class="text-2xl font-extrabold text-slate-900">24/7</div>
          <div class="text-xs text-slate-500">Support</div>
        </div>
      </div>
    </div>
  </div>

  {{-- Story + Values --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <div class="lg:col-span-2">
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Our Story</h2>
        <p class="mt-3 text-slate-600">
          From a small desk to a full-stack eCommerce and service team, we’ve kept the same promise:
          bring great tech to Nepal with fair pricing and delightful service.
        </p>

        {{-- Gallery row --}}
        <div class="mt-6 grid grid-cols-3 gap-3">
          <img class="h-40 w-full object-cover rounded-2xl ring-1 ring-slate-200"
               src="{{ asset('1.jpeg') }}"
               onerror="this.src='https://images.unsplash.com/photo-1518770660439-4636190af475?q=80&w=1200&auto=format&fit=crop';"
               alt="Workbench">
          <img class="h-40 w-full object-cover rounded-2xl ring-1 ring-slate-200"
               src="{{ asset('2.jpeg') }}"
               onerror="this.src='https://images.unsplash.com/photo-1526947425960-945c6e72858f?q=80&w=1200&auto=format&fit=crop';"
               alt="Team">
          <img class="h-40 w-full object-cover rounded-2xl ring-1 ring-slate-200"
               src="{{ asset('3.jpeg') }}"
               onerror="this.src='https://images.unsplash.com/photo-1517336714731-489689fd1ca8?q=80&w=1200&auto=format&fit=crop';"
               alt="Storefront">
        </div>
      </div>

      <div>
        <h3 class="text-lg font-bold text-slate-900">Our Values</h3>
        <ul class="mt-3 space-y-3">
          <li class="rounded-xl bg-white p-4 shadow ring-1 ring-slate-200">
            <p class="font-semibold">Honesty</p><p class="text-sm text-slate-600">Transparent pricing & warranty.</p>
          </li>
          <li class="rounded-xl bg-white p-4 shadow ring-1 ring-slate-200">
            <p class="font-semibold">Performance</p><p class="text-sm text-slate-600">We test and curate.</p>
          </li>
          <li class="rounded-xl bg-white p-4 shadow ring-1 ring-slate-200">
            <p class="font-semibold">Care</p><p class="text-sm text-slate-600">We support before and after you buy.</p>
          </li>
        </ul>
      </div>
    </div>
  </div>

  {{-- CTA --}}
  <div class="bg-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-center">
      <h3 class="text-2xl font-extrabold text-white">Ready to upgrade your tech?</h3>
      <p class="mt-2 text-slate-300">Find the right device or ask our team for advice.</p>
      <div class="mt-6">
        <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-5 py-2.5 text-white font-semibold shadow hover:bg-emerald-600">
          Talk to Us
        </a>
      </div>
    </div>
  </div>
</section>
@endsection
