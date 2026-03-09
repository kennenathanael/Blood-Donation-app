@extends('layouts.admin')
@section('title', $user->name)
@section('page-title', 'Donor Profile')
@section('page-subtitle', 'Full donor details and registration history')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- LEFT: Donor Info Card --}}
    <div class="space-y-5">

        {{-- Profile Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="blood-gradient px-6 py-8 text-center text-white">
                <div class="w-20 h-20 mx-auto bg-white/20 border-2 border-white/40 rounded-2xl flex items-center justify-center text-3xl font-bold font-display mb-3">
                    {{ $user->initials }}
                </div>
                <h2 class="font-semibold text-lg">{{ $user->name }}</h2>
                <p class="text-white/70 text-sm mt-0.5">{{ $user->email }}</p>
                @if($user->bloodGroup)
                    <span class="inline-block mt-2 bg-white/20 border border-white/30 text-white text-sm px-3 py-1 rounded-full font-bold">
                        🩸 {{ $user->bloodGroup->name }}
                    </span>
                @endif
            </div>

            <div class="p-5 space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Phone</span>
                    <span class="font-medium text-gray-700">{{ $user->phone ?? '—' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">City</span>
                    <span class="font-medium text-gray-700">{{ $user->city ?? '—' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Gender</span>
                    <span class="font-medium text-gray-700">{{ ucfirst($user->gender ?? '—') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Date of Birth</span>
                    <span class="font-medium text-gray-700">
                        {{ $user->date_of_birth ? $user->date_of_birth->format('M j, Y') . ' (Age ' . $user->age . ')' : '—' }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Joined</span>
                    <span class="font-medium text-gray-700">{{ $user->created_at->format('M j, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Last Donation</span>
                    <span class="font-medium text-gray-700">
                        {{ $user->last_donation_date ? $user->last_donation_date->format('M j, Y') : 'Never' }}
                    </span>
                </div>
                <div class="flex justify-between items-center pt-3 border-t border-gray-50">
                    <span class="text-gray-400">Eligibility</span>
                    @if($user->is_eligible)
                        <span class="bg-green-100 text-green-700 text-xs px-2.5 py-1 rounded-full font-semibold">✓ Eligible</span>
                    @else
                        <span class="bg-red-100 text-red-600 text-xs px-2.5 py-1 rounded-full font-semibold">✕ Ineligible</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Stats Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-800 mb-4 text-sm">Donation Statistics</h3>
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-gray-50 rounded-xl p-3 text-center">
                    <p class="text-2xl font-bold text-gray-900">{{ $registrationStats['total'] }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Total Applied</p>
                </div>
                <div class="bg-blue-50 rounded-xl p-3 text-center">
                    <p class="text-2xl font-bold text-blue-700">{{ $registrationStats['donated'] }}</p>
                    <p class="text-xs text-blue-400 mt-0.5">Donated</p>
                </div>
                <div class="bg-green-50 rounded-xl p-3 text-center">
                    <p class="text-2xl font-bold text-green-700">{{ $registrationStats['accepted'] }}</p>
                    <p class="text-xs text-green-400 mt-0.5">Accepted</p>
                </div>
                <div class="bg-yellow-50 rounded-xl p-3 text-center">
                    <p class="text-2xl font-bold text-yellow-700">{{ $registrationStats['pending'] }}</p>
                    <p class="text-xs text-yellow-400 mt-0.5">Pending</p>
                </div>
            </div>
        </div>

        {{-- Medical Info --}}
        @if($user->medical_conditions)
        <div class="bg-orange-50 border border-orange-100 rounded-2xl p-5">
            <h4 class="font-semibold text-orange-800 text-sm mb-2">⚕️ Medical Conditions</h4>
            <p class="text-orange-700 text-sm leading-relaxed">{{ $user->medical_conditions }}</p>
        </div>
        @endif

        {{-- Actions --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-2">
            <h3 class="font-semibold text-gray-800 text-sm mb-3">Admin Actions</h3>
            <form action="/admin/donors/{{ $user->id }}/toggle-eligibility" method="POST">
                @csrf
                <button type="submit"
                        class="w-full {{ $user->is_eligible ? 'bg-orange-50 text-orange-600 border-orange-200 hover:bg-orange-100' : 'bg-green-50 text-green-700 border-green-200 hover:bg-green-100' }} border py-2.5 rounded-xl text-sm font-medium transition">
                    {{ $user->is_eligible ? '✕ Mark as Ineligible' : '✓ Mark as Eligible' }}
                </button>
            </form>
            <form action="/admin/donors/{{ $user->id }}" method="POST">
                @csrf @method('DELETE')
                <button type="submit"
                        onclick="return confirm('Permanently remove {{ $user->name }} from the system? This cannot be undone.')"
                        class="w-full bg-red-50 text-red-600 border border-red-200 hover:bg-red-100 py-2.5 rounded-xl text-sm font-medium transition">
                    🗑 Remove Donor Account
                </button>
            </form>
            <a href="/admin/donors" class="block text-center border border-gray-200 text-gray-500 hover:bg-gray-50 py-2.5 rounded-xl text-sm font-medium transition">
                ← Back to All Donors
            </a>
        </div>
    </div>

    {{-- RIGHT: Registration History --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-50">
                <h3 class="font-semibold text-gray-900 text-base">Registration History</h3>
                <p class="text-gray-400 text-xs mt-0.5">All campaign registrations by this donor</p>
            </div>

            @if($user->registrations->isEmpty())
                <div class="text-center py-16 text-gray-400">
                    <div class="text-5xl mb-3 opacity-20">📋</div>
                    <p class="text-sm">No registrations yet.</p>
                </div>
            @else
                <div class="divide-y divide-gray-50">
                    @foreach($user->registrations->sortByDesc('created_at') as $reg)
                    <div class="px-6 py-5 hover:bg-gray-50 transition">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 blood-gradient rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                                    <span class="text-white text-sm">🩸</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 text-sm">{{ $reg->campaign->title ?? 'Campaign Deleted' }}</p>
                                    @if($reg->campaign)
                                        <p class="text-gray-400 text-xs mt-0.5">
                                            📍 {{ $reg->campaign->location }}
                                            · 📅 {{ $reg->campaign->campaign_date->format('M j, Y') }}
                                        </p>
                                    @endif
                                    <p class="text-gray-400 text-xs mt-1">
                                        Registered: {{ $reg->registered_at->format('M j, Y H:i') }}
                                        @if($reg->donated_at)
                                            · Donated: {{ $reg->donated_at->format('M j, Y') }}
                                        @endif
                                    </p>
                                    @if($reg->health_notes)
                                        <p class="text-gray-500 text-xs mt-1 italic bg-gray-50 px-2 py-1 rounded">
                                            "{{ Str::limit($reg->health_notes, 80) }}"
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="status-{{ $reg->status }} text-xs px-2.5 py-1 rounded-full font-semibold">
                                    {{ $reg->statusLabel() }}
                                </span>
                                @if($reg->campaign)
                                <div class="mt-2">
                                    <a href="/admin/campaigns/{{ $reg->campaign->id }}"
                                       class="text-blood-600 text-xs hover:underline">
                                        View Campaign →
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
