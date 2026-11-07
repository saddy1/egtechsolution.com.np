@extends('frontend.layouts.app')

@section('title', 'Enter your code | EG Tech Solution')

@section('content')
<section class="flex min-h-[70vh] items-center justify-center bg-slate-50 px-4 py-12">
  <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
    <h1 class="text-2xl font-bold text-slate-900">Enter your code</h1>

    @if (session('status'))
      <p class="mt-3 rounded-lg bg-emerald-50 p-3 text-sm text-emerald-800">{{ session('status') }}</p>
    @else
      <p class="mt-2 text-sm text-slate-600">We sent a 6-digit code to <strong>{{ $email }}</strong>.</p>
    @endif

    <form action="{{ route($r['update']) }}" method="POST" class="mt-6 space-y-4">
      @csrf
      <input type="hidden" name="email" value="{{ $email }}">

      <div>
        <label for="code" class="block text-sm font-medium text-slate-700">6-digit code</label>
        <input id="code" name="code" required autofocus inputmode="numeric" autocomplete="one-time-code" maxlength="6" pattern="\d{6}"
               class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2.5 text-center text-2xl tracking-[0.5em] focus:border-brand-navy focus:outline-none focus:ring-2 focus:ring-brand-navy/20">
        @error('code')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
      </div>

      <div>
        <label for="password" class="block text-sm font-medium text-slate-700">New password</label>
        <input id="password" type="password" name="password" required minlength="8" autocomplete="new-password"
               class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-brand-navy focus:outline-none focus:ring-2 focus:ring-brand-navy/20">
        <p class="mt-1 text-xs text-slate-500">At least 8 characters.</p>
        @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
      </div>

      <div>
        <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirm new password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password"
               class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-brand-navy focus:outline-none focus:ring-2 focus:ring-brand-navy/20">
      </div>

      <button type="submit" class="w-full rounded-lg bg-brand-orange px-4 py-2.5 font-semibold text-white hover:bg-brand-orange-dark">
        Set new password
      </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-600">
      No code? Check spam, or <a href="{{ route($r['request']) }}" class="font-semibold text-brand-navy hover:underline">send a new one</a>.
    </p>
  </div>
</section>
@endsection
