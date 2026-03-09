@extends('layouts.app')
@section('title', 'Blood Donation Campaigns')

@section('content')
<div class="bg-gradient-to-br from-blood-900 to-gray-900 text-white py-16">
    <div class="max-w-7xl mx-auto px-6">
        <h1 class="font-display text-4xl font-bold mb-2">Blood Donation Campaigns</h1>
        <p class="text-white/70 text-lg">Find a campaign near you and register to donate.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 py-10">

    {{-- Filters --}}
    <form method="GET" action="/campaigns" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Campaign name or location..."
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Status</label>
                <select name="status" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blood-300 transition">
                    <option value="">All Statuses</option>
                    <option value="active"    {{ request('status') == 'active'    ? 'selected' : '' }}>Active</option>
                    <option value="upcoming"  {{ request('status') == 'upcoming'  ? 'selected' : '' }}>Upcoming</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="blood-gradient text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:opacity-90 transition shadow">
                    🔍 Search
                </button>
                <a href="/campaigns" class="px-5 py-2.5 rounded-xl text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                    Clear
                </a>
            </div>
        </div>
    </form>

    {{-- Results --}}
    @if($campaigns->isEmpty())
        <div class="text-center py-24">
            <div class="text-7xl mb-4 opacity-30">📅</div>
            <h3 class="text-xl font-semibold text-gray-500">No campaigns found</h3>
            <p class="text-gray-400 mt-2 text-sm">Try adjusting your search filters.</p>
        </div>
    @else
        <div class="mb-4 text-sm text-gray-500">
            Showing <strong>{{ $campaigns->total() }}</strong> campaign(s)
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($campaigns as $campaign)
            <a href="/campaigns/{{ $campaign->id }}" class="block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden card-hover group">

                {{-- Banner or placeholder --}}
                @if($campaign->banner_image)
                    <img src="{{ asset('storage/' . $campaign->banner_image) }}" alt="{{ $campaign->title }}"
                         class="w-full h-44 object-cover group-hover:scale-105 transition duration-300">
                @else
                    <div class="w-full h-44 bg-gradient-to-br from-blood-50 via-blood-100 to-red-50 flex items-center justify-center relative overflow-hidden">
                        <span class="text-8xl opacity-20">🩸</span>
                        <div class="absolute inset-0 blood-gradient opacity-10"></div>
                    </div>
                @endif

                <div class="p-5">
                    {{-- Status & Days badge --}}
                    <div class="flex items-center justify-between mb-3">
                        <span class="status-{{ $campaign->status }} text-xs px-2.5 py-1 rounded-full font-medium">
                            {{ ucfirst($campaign->status) }}
                        </span>
                        @if($campaign->campaign_date->isFuture())
                            <span class="text-xs text-gray-400 bg-gray-50 px-2 py-1 rounded-full">
                                {{ $campaign->daysUntil() }}d away
                            </span>
                        @endif
                    </div>

                    <h3 class="font-semibold text-gray-900 text-base leading-snug group-hover:text-blood-700 transition mb-2">
                        {{ $campaign->title }}
                    </h3>

                    <div class="space-y-1 mb-4">
                        <p class="text-gray-500 text-xs flex items-center gap-1.5">
                            <span>📍</span> {{ $campaign->location }}
                        </p>
                        <p class="text-gray-500 text-xs flex items-center gap-1.5">
                            <span>📅</span> {{ $campaign->campaign_date->format('D, M j, Y \a\t H:i') }}
                        </p>
                        <p class="text-gray-500 text-xs flex items-center gap-1.5">
                            <span>⏰</span> Deadline: {{ $campaign->registration_deadline->format('M j, Y') }}
                        </p>
                    </div>

                    {{-- Capacity bar --}}
                    @php $pct = $campaign->fillPercentage(); @endphp
                    <div class="mb-4">
                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                            <span>{{ $campaign->accepted_registrations_count }} accepted</span>
                            <span>{{ $campaign->max_donors }} max</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5">
                            <div class="blood-gradient h-1.5 rounded-full transition-all"
                                 style="width: {{ $pct }}%"></div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                        @if($campaign->isOpen())
                            <span class="text-blood-600 text-xs font-semibold">
                                {{ $campaign->spotsRemaining() }} spots left
                            </span>
                            <span class="blood-gradient text-white text-xs px-3 py-1.5 rounded-lg font-medium">
                                Register →
                            </span>
                        @else
                            <span class="text-gray-400 text-xs">Registration closed</span>
                            <span class="text-gray-500 text-xs px-3 py-1.5 rounded-lg border border-gray-200">
                                View Details
                            </span>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $campaigns->links() }}
        </div>
    @endif
</div>
@endsection
