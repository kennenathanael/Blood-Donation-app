@extends('layouts.app')
@section('title', 'My Dashboard')

@section('content')
<div class="bg-gradient-to-br from-blood-900 to-gray-900 text-white py-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 blood-gradient rounded-2xl flex items-center justify-center text-2xl font-bold shadow-lg border-2 border-white/20">
                {{ $user->initials }}
            </div>
            <div>
                <p class="text-white/60 text-sm">Welcome back,</p>
                <h1 class="font-display text-3xl font-bold">{{ $user->name }}</h1>
                @if($user->bloodGroup)
                    <span class="inline-block mt-1 bg-blood-700/50 border border-blood-500/30 text-blood-200 text-xs px-3 py-1 rounded-full">
                        🩸 Blood Type: {{ $user->bloodGroup->name }}
                    </span>
                @else
                    <a href="/donor/profile" class="inline-block mt-1 bg-yellow-500/20 border border-yellow-400/30 text-yellow-200 text-xs px-3 py-1 rounded-full hover:bg-yellow-500/30 transition">
                        ⚠️ Add your blood group →
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 py-8">

    {{-- ── Stats ── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @foreach([
            ['label' => 'Total Registrations', 'value' => $totalRegistrations, 'icon' => '📋', 'color' => 'blood'],
            ['label' => 'Pending',  'value' => $pendingCount,  'icon' => '⏳', 'color' => 'yellow'],
            ['label' => 'Accepted', 'value' => $acceptedCount, 'icon' => '✅', 'color' => 'green'],
            ['label' => 'Donated',  'value' => $donatedCount,  'icon' => '🩸', 'color' => 'blue'],
        ] as $stat)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="text-2xl mb-2">{{ $stat['icon'] }}</div>
            <p class="text-2xl font-bold text-gray-900">{{ $stat['value'] }}</p>
            <p class="text-xs text-gray-400 mt-0.5 font-medium">{{ $stat['label'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- ── Upcoming Donations ── --}}
    @if($upcomingDonations->isNotEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <h2 class="font-semibold text-gray-900 text-lg mb-4 flex items-center gap-2">
            <span class="text-green-500">✅</span> Your Upcoming Donations
        </h2>
        <div class="space-y-3">
            @foreach($upcomingDonations as $reg)
            <div class="flex items-center justify-between p-4 bg-green-50 border border-green-100 rounded-xl">
                <div>
                    <p class="font-semibold text-gray-900 text-sm">{{ $reg->campaign->title }}</p>
                    <p class="text-gray-500 text-xs mt-0.5">
                        📅 {{ $reg->campaign->campaign_date->format('D, M j, Y \a\t H:i') }}
                        — 📍 {{ $reg->campaign->location }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-green-700 text-xs bg-green-100 px-2 py-1 rounded-full font-medium">
                        {{ $reg->campaign->daysUntil() }}d away
                    </span>
                    <a href="/campaigns/{{ $reg->campaign->id }}" class="text-xs text-blood-600 hover:underline">Details</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ── Recent Activity ── --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="font-semibold text-gray-900 text-lg">Recent Activity</h2>
                <a href="/donor/registrations" class="text-blood-600 text-sm hover:underline">View all</a>
            </div>

            @if($recentRegistrations->isEmpty())
                <div class="text-center py-10">
                    <div class="text-5xl mb-3 opacity-30">📋</div>
                    <p class="text-gray-500 text-sm">No registrations yet.</p>
                    <a href="/campaigns" class="mt-3 inline-block blood-gradient text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Browse Campaigns →
                    </a>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($recentRegistrations as $reg)
                    <div class="flex items-center gap-4 p-4 rounded-xl border border-gray-50 hover:bg-gray-50 transition">
                        <div class="w-10 h-10 blood-gradient rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-sm">🩸</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 text-sm truncate">{{ $reg->campaign->title }}</p>
                            <p class="text-gray-400 text-xs mt-0.5">
                                📅 {{ $reg->campaign->campaign_date->format('M j, Y') }}
                                · Registered {{ $reg->registered_at->diffForHumans() }}
                            </p>
                        </div>
                        <span class="status-{{ $reg->status }} text-xs px-2.5 py-1 rounded-full font-medium flex-shrink-0">
                            {{ $reg->statusLabel() }}
                        </span>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ── Quick Links + Profile Completion ── --}}
        <div class="space-y-5">

            {{-- Notifications --}}
            @if($unreadNotifications > 0)
            <div class="bg-blood-50 border border-blood-100 rounded-2xl p-5">
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-2xl">🔔</span>
                    <h3 class="font-semibold text-blood-800">{{ $unreadNotifications }} New Notification(s)</h3>
                </div>
                <p class="text-blood-600 text-sm mb-3">You have unread notifications about your registrations.</p>
                <a href="/donor/notifications" class="blood-gradient text-white px-4 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition inline-block">
                    View Notifications
                </a>
            </div>
            @endif

            {{-- Quick Actions --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h3 class="font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="/campaigns" class="flex items-center gap-3 p-3 rounded-xl hover:bg-blood-50 text-sm text-gray-700 transition group">
                        <span class="w-8 h-8 blood-gradient rounded-lg flex items-center justify-center text-white group-hover:scale-110 transition">🔍</span>
                        Find Campaigns
                    </a>
                    <a href="/donor/profile" class="flex items-center gap-3 p-3 rounded-xl hover:bg-blood-50 text-sm text-gray-700 transition group">
                        <span class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center text-white group-hover:scale-110 transition">👤</span>
                        Edit Profile
                    </a>
                    <a href="/donor/history" class="flex items-center gap-3 p-3 rounded-xl hover:bg-blood-50 text-sm text-gray-700 transition group">
                        <span class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center text-white group-hover:scale-110 transition">📜</span>
                        Donation History
                    </a>
                    <a href="/donor/notifications" class="flex items-center gap-3 p-3 rounded-xl hover:bg-blood-50 text-sm text-gray-700 transition group">
                        <span class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center text-white group-hover:scale-110 transition">🔔</span>
                        Notifications
                        @if($unreadNotifications > 0)
                            <span class="ml-auto blood-gradient text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold">{{ $unreadNotifications }}</span>
                        @endif
                    </a>
                </div>
            </div>

            {{-- Donor card --}}
            <div class="blood-gradient rounded-2xl p-5 text-white">
                <p class="text-white/70 text-xs uppercase tracking-wider mb-3">Donor Card</p>
                <p class="font-display text-xl font-bold">{{ $user->name }}</p>
                <div class="flex items-center justify-between mt-3">
                    @if($user->bloodGroup)
                        <div class="bg-white/20 rounded-xl px-4 py-2 text-center">
                            <p class="text-2xl font-display font-bold">{{ $user->bloodGroup->name }}</p>
                            <p class="text-white/60 text-xs">Blood Type</p>
                        </div>
                    @endif
                    <div class="text-right">
                        <p class="text-2xl font-bold">{{ $donatedCount }}</p>
                        <p class="text-white/60 text-xs">Donations</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
