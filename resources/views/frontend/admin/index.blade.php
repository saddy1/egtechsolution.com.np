@extends('frontend.layouts.app')

@push('head')
    <title>ADMIN LOGIN</title>
@endpush

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
    <div class="w-full max-w-md">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h3 class="text-center text-2xl font-semibold mb-6">Admin Login</h3>

            @if (session('status'))
                <div class="bg-green-100 text-green-800 p-3 mb-4 rounded text-center">{{ session('status') }}</div>
            @endif

            @if (session('error'))
                <div id="error-message" class="bg-red-100 text-red-700 p-3 mb-4 rounded text-center">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block mb-1 font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block mb-1 font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <button type="submit"
                        class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded flex items-center justify-center gap-2">
                        <span>Log In</span>
                        <i class="fa fa-check"></i>
                    </button>
                </div>

                <p class="text-center mt-3 text-gray-600 text-sm">
                    <a href="{{ route('admin.password.request') }}" class="text-red-600 hover:underline">Forgot your password?</a>
                </p>
            </form>

            <div id="passkeyBox" class="mt-6 hidden">
                <div class="flex items-center gap-3 text-xs uppercase tracking-wider text-gray-400">
                    <span class="h-px flex-1 bg-gray-200"></span> or <span class="h-px flex-1 bg-gray-200"></span>
                </div>
                <button type="button" id="passkeyBtn"
                    class="mt-4 w-full rounded border border-gray-300 bg-white px-4 py-2 font-semibold text-gray-800 hover:bg-gray-50 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-fingerprint"></i> <span>Sign in with a passkey</span>
                </button>
                <p id="passkeyError" class="mt-2 hidden text-center text-sm text-red-600"></p>
            </div>
        </div>
    </div>
</div>

@include('backend.partials.passkey-js')
<script>
    (function () {
        if (!EGPasskey.supported()) return;
        const box = document.getElementById('passkeyBox'), btn = document.getElementById('passkeyBtn'), err = document.getElementById('passkeyError');
        box.classList.remove('hidden');
        btn.addEventListener('click', async () => {
            err.classList.add('hidden');
            btn.disabled = true;
            try {
                const res = await EGPasskey.login();
                window.location = res.redirect;
            } catch (e) {
                err.textContent = e.message;
                err.classList.remove('hidden');
                btn.disabled = false;
            }
        });
    })();

    document.getElementById('email').addEventListener('focus', function() {
        let errorDiv = document.getElementById('error-message');
        if(errorDiv) errorDiv.style.display = 'none';
    });

    document.getElementById('password').addEventListener('focus', function() {
        let errorDiv = document.getElementById('error-message');
        if(errorDiv) errorDiv.style.display = 'none';
    });
</script>
@endsection
