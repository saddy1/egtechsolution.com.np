@extends('frontend.layouts.app')

@push('head')
    <title>SIGN UP</title>
@endpush

@section('content')

<div class="flex items-center justify-center min-h-screen bg-gray-50 px-4">
    <div class="w-full max-w-lg">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h3 class="text-2xl font-semibold text-center mb-6">Create Your Account</h3>

            @if (Session::has('success'))
                <div class="bg-green-100 text-green-700 text-center py-2 px-4 rounded mb-4">
                    {{ Session::get('success') }}
                </div>
            @endif

            <form action="{{ route('customer.store') }}" method="post" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <x-spam-guard />

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fa fa-user"></i> Full Name <span class="text-red-600">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-500"
                           required autofocus autocomplete="name">
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fa fa-phone"></i> Phone <span class="text-red-600">*</span>
                    </label>
                    <input type="text" id="phone" name="number" value="{{ old('number') }}"
                           class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-500"
                           required autocomplete="tel">
                    @error('number')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="emailname" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fa fa-envelope"></i> Email Address <span class="text-red-600">*</span>
                    </label>
                    <input type="email" id="emailname" name="email" value="{{ old('email') }}"
                           class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-500"
                           required autocomplete="email">
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="pass" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fa fa-key"></i> Password <span class="text-red-600">*</span>
                    </label>
                    <input type="password" id="pass" name="password"
                           class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-500"
                           required autocomplete="new-password">
                    @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="compass" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fa fa-key"></i> Confirm Password <span class="text-red-600">*</span>
                    </label>
                    <input type="password" id="compass" name="password_confirmation"
                           class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-500"
                           required autocomplete="new-password">
                    @error('password_confirmation')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700 transition flex items-center justify-center gap-2">
                    <span>Sign Up</span> <i class="fa fa-check"></i>
                </button>
            </form>

            <p class="text-center text-sm mt-4">
                Already have an account? 
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a>
            </p>
        </div>
    </div>
</div>

@endsection
