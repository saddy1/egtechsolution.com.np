@extends('frontend.layouts.app')

@push('head')
    <title>Login EG Tech Solution</title>
@endpush

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-50 px-4 ">
    <div class="w-full max-w-md">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h3 class="text-2xl font-semibold text-center mb-6">Login</h3>

            @if (session('error'))
                <div class="bg-red-100 text-red-700 text-center py-2 px-4 rounded mb-4" id="error-message">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ url('/') }}/auth" method="post" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" 
                           class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           required autofocus autocomplete="email">
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" 
                           class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           required autocomplete="current-password">
                    @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-between text-sm">
                    <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline">
                        Forgot your password?
                    </a>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                    Log In
                </button>

                <p class="text-center text-sm mt-4">
                    Not a member? 
                    <a href="{{ route('customer.store') }}" class="text-blue-600 hover:underline">Sign up now</a>
                </p>

                <a href="{{ url('auth/google') }}" class="w-full inline-flex items-center justify-center mt-2 bg-red-600 text-white py-2 rounded hover:bg-red-700 transition">
                    <i class="fab fa-google mr-2"></i> Login with Google
                </a>
            </form>
        </div>
    </div>
</div>

<script>
    const errorMessage = document.getElementById('error-message');
    if(errorMessage) {
        document.getElementById('email').addEventListener('focus', () => errorMessage.style.display = 'none');
        document.getElementById('password').addEventListener('focus', () => errorMessage.style.display = 'none');
    }
</script>
@endsection
