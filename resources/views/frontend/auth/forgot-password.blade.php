@extends('frontend.layouts.app')

@section('title', ($guard === 'admin' ? 'Admin: ' : '') . 'Forgot password | EG Tech Solution')

@section('content')
<section class="flex min-h-[70vh] items-center justify-center bg-slate-50 px-4 py-12">
  <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
    <h1 class="text-2xl font-bold text-slate-900">Forgot your {{ $guard === 'admin' ? 'admin ' : '' }}password?</h1>
    <p class="mt-2 text-sm text-slate-600">Enter your email and we'll send you a 6-digit code to set a new password.</p>

    <form action="{{ route($r['send']) }}" method="POST" class="mt-6 space-y-4">
      @csrf
      <div>
        <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
               class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-brand-navy focus:outline-none focus:ring-2 focus:ring-brand-navy/20">
        @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
      </div>
      <button type="submit" class="w-full rounded-lg bg-brand-orange px-4 py-2.5 font-semibold text-white hover:bg-brand-orange-dark">
        Send code
      </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-600">
      Remembered it? <a href="{{ $r['login'] }}" class="font-semibold text-brand-navy hover:underline">Back to log in</a>
    </p>
  </div>
</section>
@endsection
