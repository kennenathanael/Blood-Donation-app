@extends('layouts.app')
@section('title', 'Register as Donor')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blood-50 py-12 px-4">
    <div class="w-full max-w-2xl mx-auto">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-2">
                <div class="w-12 h-12 blood-gradient rounded-2xl flex items-center justify-center shadow-lg">
                    <span class="text-white text-2xl">🩸</span>
                </div>
                <span class="font-display text-2xl font-bold text-blood-700">BloodLife</span>
            </a>
            <h2 class="text-2xl font-bold text-gray-800 mt-4">Create Donor Account</h2>
            <p class="text-gray-500 text-sm mt-1">Join our community of life-saving donors</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <p class="text-red-700 font-semibold text-sm mb-2">Please fix the following:</p>
                @foreach($errors->all() as $error)
                    <p class="text-red-600 text-sm">• {{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form action="/register" method="POST" class="space-y-5">
                @csrf

                {{-- Name & Email --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name <span class="text-blood-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition @error('name') border-red-400 @enderror"
                               placeholder="John Doe">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address <span class="text-blood-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition @error('email') border-red-400 @enderror"
                               placeholder="you@example.com">
                    </div>
                </div>

                {{-- Phone & DOB --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone Number</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition"
                               placeholder="+237 600 000 000">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Date of Birth <span class="text-xs text-gray-400">(must be 18+)</span></label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition @error('date_of_birth') border-red-400 @enderror">
                    </div>
                </div>

                {{-- Gender & Blood Group --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Gender</label>
                        <select name="gender" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition bg-white">
                            <option value="">Select gender</option>
                            <option value="male"   {{ old('gender') == 'male'   ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other"  {{ old('gender') == 'other'  ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Blood Group</label>
                        <select name="blood_group_id" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition bg-white">
                            <option value="">Select blood group</option>
                            @foreach($bloodGroups as $bg)
                                <option value="{{ $bg->id }}" {{ old('blood_group_id') == $bg->id ? 'selected' : '' }}>
                                    {{ $bg->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- City --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">City</label>
                    <input type="text" name="city" value="{{ old('city') }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition"
                           placeholder="e.g. Yaoundé">
                </div>

                {{-- Password --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Password <span class="text-blood-500">*</span></label>
                        <input type="password" name="password" required minlength="8"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition @error('password') border-red-400 @enderror"
                               placeholder="Minimum 8 characters">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm Password <span class="text-blood-500">*</span></label>
                        <input type="password" name="password_confirmation" required
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition"
                               placeholder="Repeat your password">
                    </div>
                </div>

                {{-- Terms --}}
                <div class="bg-blood-50 border border-blood-100 rounded-xl p-4">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" required class="mt-0.5 rounded border-gray-300 text-blood-600">
                        <span class="text-sm text-gray-600">
                            I confirm that I am <strong>18+ years old</strong>, in good health, and agree to be contacted about blood donation campaigns. I understand my data will be used only for campaign purposes.
                        </span>
                    </label>
                </div>

                <button type="submit"
                        class="w-full blood-gradient text-white py-3.5 rounded-xl font-semibold text-sm hover:opacity-90 transition shadow-lg">
                    🩸 Create Donor Account
                </button>
            </form>
        </div>

        <p class="text-center text-sm text-gray-500 mt-6">
            Already have an account?
            <a href="/login" class="text-blood-600 font-semibold hover:underline">Sign in</a>
        </p>
    </div>
</div>
@endsection
