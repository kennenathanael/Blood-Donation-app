@extends('layouts.admin')
@section('title', 'Edit Campaign')
@section('page-title', 'Edit: ' . $campaign->title)
@section('page-subtitle', 'Update campaign details')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form action="/admin/campaigns/{{ $campaign->id }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Campaign Title *</label>
                <input type="text" name="title" value="{{ old('title', $campaign->title) }}" required
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Description</label>
                <textarea name="description" rows="4"
                          class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition resize-none">{{ old('description', $campaign->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Location *</label>
                    <input type="text" name="location" value="{{ old('location', $campaign->location) }}" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Address</label>
                    <input type="text" name="address" value="{{ old('address', $campaign->address) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Campaign Date *</label>
                    <input type="datetime-local" name="campaign_date"
                           value="{{ old('campaign_date', $campaign->campaign_date->format('Y-m-d\TH:i')) }}" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Deadline *</label>
                    <input type="datetime-local" name="registration_deadline"
                           value="{{ old('registration_deadline', $campaign->registration_deadline->format('Y-m-d\TH:i')) }}" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Status *</label>
                    <select name="status" required
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blood-300 transition">
                        @foreach(['active', 'upcoming', 'completed', 'cancelled'] as $s)
                            <option value="{{ $s }}" {{ old('status', $campaign->status) === $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Max Donors *</label>
                    <input type="number" name="max_donors" value="{{ old('max_donors', $campaign->max_donors) }}" required min="1"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Contact Phone</label>
                    <input type="tel" name="contact_phone" value="{{ old('contact_phone', $campaign->contact_phone) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Contact Email</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', $campaign->contact_email) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Requirements</label>
                    <textarea name="requirements" rows="4"
                              class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition resize-none">{{ old('requirements', $campaign->requirements) }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Benefits</label>
                    <textarea name="benefits" rows="4"
                              class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition resize-none">{{ old('benefits', $campaign->benefits) }}</textarea>
                </div>
            </div>

            @if($campaign->banner_image)
            <div>
                <p class="text-xs text-gray-500 mb-2">Current banner:</p>
                <img src="{{ asset('storage/' . $campaign->banner_image) }}" class="h-24 rounded-xl object-cover">
            </div>
            @endif
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Replace Banner Image</label>
                <input type="file" name="banner_image" accept="image/*"
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none transition">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="blood-gradient text-white px-8 py-3 rounded-xl font-semibold text-sm hover:opacity-90 transition shadow">
                    Save Changes
                </button>
                <a href="/admin/campaigns/{{ $campaign->id }}" class="px-6 py-3 rounded-xl text-sm font-medium text-gray-600 border border-gray-200 hover:bg-gray-50 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
