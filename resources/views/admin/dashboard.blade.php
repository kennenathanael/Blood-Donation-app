@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Overview of your blood donation platform')

@section('content')

{{-- ── Stats Cards ── --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
    @foreach([
        ['label' => 'Total Campaigns',  'value' => $totalCampaigns,   'icon' => '📅', 'sub' => 'All time', 'color' => 'blood'],
        ['label' => 'Active Campaigns', 'value' => $activeCampaigns,  'icon' => '🟢', 'sub' => 'Open now', 'color' => 'green'],
        ['label' => 'Total Donors',     'value' => $totalDonors,      'icon' => '👥', 'sub' => 'Registered', 'color' => 'blue'],
        ['label' => 'Total Donations',  'value' => $totalDonations,   'icon' => '🩸', 'sub' => 'Completed', 'color' => 'blood'],
        ['label' => 'Pending Approvals','value' => $pendingApprovals, 'icon' => '⏳', 'sub' => 'Need review', 'color' => 'yellow'],
    ] as $stat)
    <div class="bg-white rounded-2xl shadow-sm border {{ $stat['value'] > 0 && $stat['label'] === 'Pending Approvals' ? 'border-yellow-200' : 'border-gray-100' }} p-5">
        <div class="text-2xl mb-2">{{ $stat['icon'] }}</div>
        <p class="text-2xl font-bold text-gray-900">{{ $stat['value'] }}</p>
        <p class="text-sm font-medium text-gray-600 mt-0.5">{{ $stat['label'] }}</p>
        <p class="text-xs text-gray-400 mt-0.5">{{ $stat['sub'] }}</p>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- ── Recent Campaigns ── --}}
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-semibold text-gray-900 text-lg">Recent Campaigns</h2>
            <a href="/admin/campaigns" class="text-blood-600 text-sm hover:underline">View all →</a>
        </div>

        @if($recentCampaigns->isEmpty())
            <div class="text-center py-10 text-gray-400">
                <p class="text-4xl mb-3 opacity-30">📅</p>
                <p class="text-sm">No campaigns yet.</p>
                <a href="/admin/campaigns/create" class="mt-3 inline-block text-blood-600 text-sm hover:underline">Create one →</a>
            </div>
        @else
            <div class="space-y-3">
                @foreach($recentCampaigns as $campaign)
                <div class="flex items-center justify-between p-4 rounded-xl border border-gray-50 hover:bg-gray-50 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 blood-gradient rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-sm">📅</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 text-sm">{{ $campaign->title }}</p>
                            <p class="text-gray-400 text-xs">
                                {{ $campaign->campaign_date->format('M j, Y') }}
                                · {{ $campaign->accepted_registrations_count }}/{{ $campaign->max_donors }} donors
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="status-{{ $campaign->status }} text-xs px-2 py-1 rounded-full font-medium">
                            {{ ucfirst($campaign->status) }}
                        </span>
                        <a href="/admin/campaigns/{{ $campaign->id }}" class="text-gray-400 hover:text-blood-600 text-xs">View →</a>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ── Blood Group Stats + Pending ── --}}
    <div class="space-y-5">

        {{-- Pending Approvals --}}
        @if($pendingApprovals > 0)
        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-5">
            <div class="flex items-center gap-3 mb-3">
                <span class="text-2xl">⏳</span>
                <div>
                    <p class="font-semibold text-yellow-800 text-sm">{{ $pendingApprovals }} Pending</p>
                    <p class="text-yellow-600 text-xs">Registrations need review</p>
                </div>
            </div>
            <a href="/admin/campaigns" class="block text-center bg-yellow-500 text-white py-2 rounded-xl text-sm font-medium hover:bg-yellow-600 transition">
                Review Now →
            </a>
        </div>
        @endif

        {{-- Blood Group Distribution --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-900 mb-4">Donor Blood Groups</h3>
            @if($bloodGroupStats->isEmpty())
                <p class="text-gray-400 text-sm text-center py-4">No data yet</p>
            @else
                <div class="space-y-3">
                    @foreach($bloodGroupStats as $stat)
                    <div class="flex items-center gap-3">
                        <span class="w-10 text-xs font-bold text-blood-700 bg-blood-50 px-2 py-1 rounded text-center">
                            {{ $stat['name'] }}
                        </span>
                        <div class="flex-1 bg-gray-100 rounded-full h-2">
                            @php
                                $maxCount = $bloodGroupStats->max('count');
                                $pct = $maxCount > 0 ? ($stat['count'] / $maxCount) * 100 : 0;
                            @endphp
                            <div class="blood-gradient h-2 rounded-full" style="width: {{ $pct }}%"></div>
                        </div>
                        <span class="text-xs text-gray-500 w-6 text-right">{{ $stat['count'] }}</span>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

{{-- ── Recent Registrations ── --}}
<div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-center justify-between mb-5">
        <h2 class="font-semibold text-gray-900 text-lg">Recent Registrations</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider pb-3">Donor</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider pb-3">Campaign</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider pb-3">Status</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider pb-3">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($recentRegistrations as $reg)
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-3.5">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 blood-gradient rounded-lg flex items-center justify-center text-white text-xs font-bold">
                                {{ substr($reg->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $reg->user->name }}</p>
                                <p class="text-gray-400 text-xs">{{ $reg->user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-3.5 text-gray-600">{{ Str::limit($reg->campaign->title, 30) }}</td>
                    <td class="py-3.5">
                        <span class="status-{{ $reg->status }} text-xs px-2.5 py-1 rounded-full font-medium">
                            {{ $reg->statusLabel() }}
                        </span>
                    </td>
                    <td class="py-3.5 text-gray-400 text-xs">{{ $reg->created_at->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
