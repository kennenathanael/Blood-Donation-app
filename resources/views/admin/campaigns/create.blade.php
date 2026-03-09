@extends('layouts.admin')
@section('title', 'Create Campaign')
@section('page-title', 'Create Campaign')
@section('page-subtitle', 'Set up a new blood donation campaign')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form action="/admin/campaigns" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Title --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Campaign Title *</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition @error('title') border-red-400 @enderror"
                       placeholder="e.g. Hospital Blood Drive — March 2025">
                @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Description</label>
                <textarea name="description" rows="4"
                          class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition resize-none"
                          placeholder="Describe the campaign, its purpose, and what donors can expect...">{{ old('description') }}</textarea>
            </div>

            {{-- Location --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Location Name *</label>
                    <input type="text" name="location" value="{{ old('location') }}" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition @error('location') border-red-400 @enderror"
                           placeholder="e.g. Central Hospital Hall A">
                    @error('location')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Address</label>
                    <input type="text" name="address" value="{{ old('address') }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition"
                           placeholder="Full address">
                </div>
            </div>

            {{-- Dates --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Campaign Date *</label>
                    <input type="datetime-local" name="campaign_date" value="{{ old('campaign_date') }}" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition @error('campaign_date') border-red-400 @enderror">
                    @error('campaign_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">End Time</label>
                    <input type="datetime-local" name="end_time" value="{{ old('end_time') }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Registration Deadline *</label>
                    <input type="datetime-local" name="registration_deadline" value="{{ old('registration_deadline') }}" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition @error('registration_deadline') border-red-400 @enderror">
                    @error('registration_deadline')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Max Donors & Contact --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Max Donors *</label>
                    <input type="number" name="max_donors" value="{{ old('max_donors', 100) }}" required min="1"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition @error('max_donors') border-red-400 @enderror">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Contact Phone</label>
                    <input type="tel" name="contact_phone" value="{{ old('contact_phone') }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Contact Email</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email') }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition">
                </div>
            </div>

            {{-- Requirements & Benefits --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Requirements</label>
                    <textarea name="requirements" rows="4"
                              class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition resize-none"
                              placeholder="- Valid ID required&#10;- Age 18–65&#10;- Minimum 50kg">{{ old('requirements') }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Benefits for Donors</label>
                    <textarea name="benefits" rows="4"
                              class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition resize-none"
                              placeholder="- Free health screening&#10;- Refreshments provided&#10;- Donation certificate">{{ old('benefits') }}</textarea>
                </div>
            </div>

            {{-- Banner --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Banner Image</label>
                <input type="file" name="banner_image" accept="image/*"
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition">
                <p class="text-gray-400 text-xs mt-1">JPG, PNG, WebP — max 5MB</p>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="blood-gradient text-white px-8 py-3 rounded-xl font-semibold text-sm hover:opacity-90 transition shadow">
                    🩸 Create Campaign
                </button>
                <a href="/admin/campaigns" class="px-6 py-3 rounded-xl text-sm font-medium text-gray-600 border border-gray-200 hover:bg-gray-50 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
