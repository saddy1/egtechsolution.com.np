@extends('frontend.layouts.app')

@push('head')
<title>Contact • EG Tech Solution</title>
@endpush

@section('content')
<section class="min-h-screen bg-gradient-to-b from-white to-slate-50 py-10 sm:py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="text-center">
      <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900">Let’s talk</h1>
      <p class="mt-2 text-slate-600">Questions, quotes, or support—send us a message and we’ll reply quickly.</p>
    </div>

    <div class="mt-10 grid grid-cols-1 lg:grid-cols-12 gap-8">
      {{-- Form --}}
      <div class="lg:col-span-7">
        <div class="rounded-3xl bg-white p-6 sm:p-8 shadow-sm ring-1 ring-slate-200">
          @if (session('success'))
            <div class="mb-4 rounded-xl bg-emerald-50 p-3 text-emerald-700 ring-1 ring-emerald-200">
              {{ session('success') }}
            </div>
          @endif
          @error('send')
            <div class="mb-4 rounded-xl bg-red-50 p-3 text-red-700 ring-1 ring-red-200">
              {{ $message }}
            </div>
          @enderror

          <form method="POST" action="{{ route('contact.send') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="text-xs font-semibold text-slate-700">Name</label>
                <input name="name" value="{{ old('name') }}" required
                       class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 @error('name') border-red-400 @enderror">
                @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
              </div>
              <div>
                <label class="text-xs font-semibold text-slate-700">Email</label>
                <input name="email" type="email" value="{{ old('email') }}" required
                       class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 @error('email') border-red-400 @enderror">
                @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
              </div>
              <div>
                <label class="text-xs font-semibold text-slate-700">Phone (optional)</label>
                <input name="phone" value="{{ old('phone') }}"
                       class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 @error('phone') border-red-400 @enderror">
                @error('phone')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
              </div>
              <div>
                <label class="text-xs font-semibold text-slate-700">Subject</label>
                <input name="subject" value="{{ old('subject') }}" required
                       class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 @error('subject') border-red-400 @enderror">
                @error('subject')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
              </div>
            </div>

            <div>
              <label class="text-xs font-semibold text-slate-700">Message</label>
              <textarea name="message" rows="6" required
                        class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 @error('message') border-red-400 @enderror">{{ old('message') }}</textarea>
              @error('message')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-3">
              <button type="submit"
                      class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-indigo-700">
                Send Message
              </button>
              <a href="mailto:egtechsolution1@gmail.com" class="text-sm font-semibold text-slate-600 hover:text-slate-800">Or email us directly →</a>
            </div>
          </form>
        </div>
      </div>

      {{-- Sidebar: contacts + map --}}
      <div class="lg:col-span-5">
        <div class="rounded-3xl bg-white p-6 sm:p-8 shadow-sm ring-1 ring-slate-200">
          <h3 class="text-lg font-bold text-slate-900">Contact Info</h3>
          <ul class="mt-3 space-y-3 text-sm">
            <li><span class="font-semibold">Email:</span> egtechsolution1@gmail.com</li>
            <li><span class="font-semibold">Phone:</span> +977-9864830276</li>
            <li><span class="font-semibold">Address:</span> Dhangadhi-Kailali, Nepal</li>
          </ul>

          <div class="mt-5">
            <iframe
              title="EG Tech Solution Map"
              class="w-full h-60 rounded-2xl ring-1 ring-slate-200"
              src="https://www.google.com/maps?q=EG Tech Solution, Dhangadhi-Kailali, Nepal&output=embed"
              loading="lazy"></iframe>
          </div>

          <div class="mt-6">
            <h4 class="text-sm font-semibold text-slate-800">Trusted brands</h4>
            <div class="mt-3 grid grid-cols-3 gap-3 opacity-80">
              <div class="rounded-xl bg-slate-50 p-3 text-center ring-1 ring-slate-200">Dell</div>
              <div class="rounded-xl bg-slate-50 p-3 text-center ring-1 ring-slate-200">Asus</div>
              <div class="rounded-xl bg-slate-50 p-3 text-center ring-1 ring-slate-200">HP</div>
              <div class="rounded-xl bg-slate-50 p-3 text-center ring-1 ring-slate-200">Lenovo</div>
              <div class="rounded-xl bg-slate-50 p-3 text-center ring-1 ring-slate-200">Acer</div>
              <div class="rounded-xl bg-slate-50 p-3 text-center ring-1 ring-slate-200">Apple</div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>
@endsection
