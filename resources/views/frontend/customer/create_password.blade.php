@extends('frontend.layouts.app')

@push('head')
    <title>Set New Password</title>
@endpush

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-50 px-4">
    <div class="w-full max-w-md">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h3 class="text-2xl font-semibold text-center mb-6">Set New Password</h3>

            @if (session('status'))
                <div class="bg-green-100 text-green-700 text-center py-2 px-4 rounded mb-4" id="success-message">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 text-center py-2 px-4 rounded mb-4" id="error-message">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="post" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" value="{{ $email }}" disabled
                        class="w-full px-3 py-2 border rounded bg-gray-100 cursor-not-allowed">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-500">
                    @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-500">
                    @error('password_confirmation')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700 transition">
                    Reset Password
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('password').addEventListener('focus', function() {
        const errorEl = document.getElementById('error-message');
        const successEl = document.getElementById('success-message');
        if(errorEl) errorEl.style.display = 'none';
        if(successEl) successEl.style.display = 'none';
    });
</script>
@endsection
