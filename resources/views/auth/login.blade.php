@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blood-50 flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-2">
                <div class="w-12 h-12 blood-gradient rounded-2xl flex items-center justify-center shadow-lg">
                    <span class="text-white text-2xl">🩸</span>
                </div>
                <span class="font-display text-2xl font-bold text-blood-700">BloodLife</span>
            </a>
            <h2 class="text-2xl font-bold text-gray-800 mt-4">Welcome back</h2>
            <p class="text-gray-500 text-sm mt-1">Sign in to your donor account</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">

            {{-- Validation errors --}}
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                @foreach($errors->all() as $error)
                    <p class="text-red-600 text-sm">• {{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form action="/login" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 focus:border-blood-400 transition @error('email') border-red-400 @enderror"
                           placeholder="you@example.com">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 focus:border-blood-400 transition pr-12"
                               placeholder="••••••••">
                        <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            👁
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-blood-600">
                        Remember me
                    </label>
                </div>

                <button type="submit"
                        class="w-full blood-gradient text-white py-3.5 rounded-xl font-semibold text-sm hover:opacity-90 transition shadow-lg">
                    Sign In →
                </button>
            </form>

            {{-- Demo credentials --}}
            <div class="mt-6 p-4 bg-blue-50 border border-blue-100 rounded-xl">
                <p class="text-xs text-blue-700 font-semibold mb-2">🔑 Demo Accounts:</p>
                <p class="text-xs text-blue-600">Admin: <code class="bg-blue-100 px-1 rounded">admin@hospital.com</code> / <code class="bg-blue-100 px-1 rounded">Admin@1234</code></p>
                <p class="text-xs text-blue-600 mt-1">Donor: <code class="bg-blue-100 px-1 rounded">donor@example.com</code> / <code class="bg-blue-100 px-1 rounded">Donor@1234</code></p>
            </div>
        </div>

        <p class="text-center text-sm text-gray-500 mt-6">
            Don't have an account?
            <a href="/register" class="text-blood-600 font-semibold hover:underline">Register as donor</a>
        </p>
    </div>
</div>

@push('scripts')
<script>
function togglePassword() {
    const input = document.getElementById('password');
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
@endpush
@endsection
