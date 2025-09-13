@extends('frontend.layouts.app')

@push('head')
    <title>Forgot Password</title>
@endpush

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-50 px-4">
    <div class="w-full max-w-md">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h3 class="text-2xl font-semibold text-center mb-6">Forgot Password</h3>

            @if (session('status'))
                <div class="bg-green-100 text-green-700 text-center py-2 px-4 rounded mb-4" id="success-message">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 text-red-700 text-center py-2 px-4 rounded mb-4" id="error-message">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="post" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Enter Your Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-500">
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700 transition">
                    Send Reset Link
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    const emailInput = document.getElementById('email');
    const errorEl = document.getElementById('error-message');
    const successEl = document.getElementById('success-message');

    emailInput.addEventListener('focus', function() {
        if(errorEl) errorEl.style.display = 'none';
        if(successEl) successEl.style.display = 'none';
    });
</script>
@endsection
