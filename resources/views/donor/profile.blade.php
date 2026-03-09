@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-10">
    <h1 class="font-display text-3xl font-bold text-gray-900 mb-2">My Profile</h1>
    <p class="text-gray-500 mb-8">Manage your personal information and donation preferences.</p>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Profile Photo Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
            <div class="w-24 h-24 mx-auto rounded-2xl mb-4 overflow-hidden">
                @if($user->profile_photo)
                    <img src="{{ asset('storage/' . $user->profile_photo) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full blood-gradient flex items-center justify-center text-white font-display font-bold text-3xl">
                        {{ $user->initials }}
                    </div>
                @endif
            </div>
            <h3 class="font-semibold text-gray-900">{{ $user->name }}</h3>
            @if($user->bloodGroup)
                <span class="inline-block mt-2 bg-blood-100 text-blood-700 text-sm px-3 py-1 rounded-full font-bold">
                    🩸 {{ $user->bloodGroup->name }}
                </span>
            @endif
            <div class="mt-4 pt-4 border-t border-gray-50 space-y-2 text-sm text-gray-500">
                <p>Total Donations: <strong class="text-gray-800">{{ $user->total_donations }}</strong></p>
                <p>Member since: <strong class="text-gray-800">{{ $user->created_at->format('M Y') }}</strong></p>
            </div>
        </div>

        {{-- Edit Form --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-7">
                <h2 class="font-semibold text-gray-900 text-lg mb-6">Personal Information</h2>

                <form action="/donor/profile" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Full Name *</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Phone</label>
                            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Date of Birth</label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Gender</label>
                            <select name="gender" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blood-300 transition">
                                <option value="">Select</option>
                                <option value="male"   {{ old('gender', $user->gender) == 'male'   ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other"  {{ old('gender', $user->gender) == 'other'  ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Blood Group</label>
                            <select name="blood_group_id" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blood-300 transition">
                                <option value="">Select blood group</option>
                                @foreach($bloodGroups as $bg)
                                    <option value="{{ $bg->id }}" {{ old('blood_group_id', $user->blood_group_id) == $bg->id ? 'selected' : '' }}>
                                        {{ $bg->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">City</label>
                            <input type="text" name="city" value="{{ old('city', $user->city) }}"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Address</label>
                        <input type="text" name="address" value="{{ old('address', $user->address) }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Medical Conditions / Allergies</label>
                        <textarea name="medical_conditions" rows="3"
                                  class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition resize-none"
                                  placeholder="Any medical conditions, medications, or allergies...">{{ old('medical_conditions', $user->medical_conditions) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Profile Photo</label>
                        <input type="file" name="profile_photo" accept="image/*"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition">
                        <p class="text-gray-400 text-xs mt-1">JPG, PNG up to 2MB</p>
                    </div>

                    <button type="submit"
                            class="blood-gradient text-white px-8 py-3 rounded-xl font-semibold text-sm hover:opacity-90 transition shadow">
                        Save Changes
                    </button>
                </form>
            </div>

            {{-- Change Password --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-7">
                <h2 class="font-semibold text-gray-900 text-lg mb-6">Change Password</h2>
                <form action="/donor/password" method="POST" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Current Password</label>
                        <input type="password" name="current_password" required
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition @error('current_password') border-red-400 @enderror">
                        @error('current_password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">New Password</label>
                            <input type="password" name="password" required minlength="8"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Confirm New Password</label>
                            <input type="password" name="password_confirmation" required
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition">
                        </div>
                    </div>
                    <button type="submit" class="border border-blood-300 text-blood-700 px-6 py-2.5 rounded-xl font-medium text-sm hover:bg-blood-50 transition">
                        Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
