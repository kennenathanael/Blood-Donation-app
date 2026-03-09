@extends('layouts.admin')
@section('title', 'Campaigns')
@section('page-title', 'Campaigns')
@section('page-subtitle', 'Manage all blood donation campaigns')

@section('content')

{{-- Filter Bar --}}
<form method="GET" action="/admin/campaigns" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
        <div>
            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Campaign title..."
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Status</label>
            <select name="status" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blood-300 transition">
                <option value="">All</option>
                <option value="active"    {{ request('status') == 'active'    ? 'selected':'' }}>Active</option>
                <option value="upcoming"  {{ request('status') == 'upcoming'  ? 'selected':'' }}>Upcoming</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected':'' }}>Completed</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected':'' }}>Cancelled</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="blood-gradient text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:opacity-90 transition">Filter</button>
            <a href="/admin/campaigns" class="px-5 py-2.5 rounded-xl text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 transition">Clear</a>
            <a href="/admin/campaigns/create" class="ml-auto blood-gradient text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:opacity-90 transition">+ Create</a>
        </div>
    </div>
</form>

{{-- Campaigns Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-5 border-b border-gray-50 flex items-center justify-between">
        <p class="text-sm text-gray-500"><strong>{{ $campaigns->total() }}</strong> campaign(s)</p>
    </div>

    @if($campaigns->isEmpty())
        <div class="text-center py-20 text-gray-400">
            <p class="text-5xl mb-4 opacity-30">📅</p>
            <p class="font-medium">No campaigns found</p>
            <a href="/admin/campaigns/create" class="mt-4 inline-block blood-gradient text-white px-5 py-2.5 rounded-xl text-sm font-semibold">
                Create First Campaign →
            </a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-5 py-3.5">Campaign</th>
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 py-3.5">Date</th>
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 py-3.5">Donors</th>
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 py-3.5">Pending</th>
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 py-3.5">Status</th>
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 py-3.5">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($campaigns as $campaign)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-4">
                            <p class="font-medium text-gray-900">{{ $campaign->title }}</p>
                            <p class="text-gray-400 text-xs mt-0.5">📍 {{ $campaign->location }}</p>
                        </td>
                        <td class="px-3 py-4 text-gray-600 text-xs">
                            {{ $campaign->campaign_date->format('M j, Y') }}
                        </td>
                        <td class="px-3 py-4">
                            <div class="flex items-center gap-2">
                                <span class="text-gray-700 font-medium text-xs">
                                    {{ $campaign->accepted_registrations_count }}/{{ $campaign->max_donors }}
                                </span>
                                <div class="w-16 bg-gray-100 rounded-full h-1.5">
                                    <div class="blood-gradient h-1.5 rounded-full" style="width: {{ $campaign->fillPercentage() }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 py-4">
                            @php $pending = $campaign->registrations->where('status', 'pending')->count(); @endphp
                            @if($pending > 0)
                                <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full font-semibold">
                                    {{ $pending }} pending
                                </span>
                            @else
                                <span class="text-gray-300 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-3 py-4">
                            <span class="status-{{ $campaign->status }} text-xs px-2.5 py-1 rounded-full font-medium">
                                {{ ucfirst($campaign->status) }}
                            </span>
                        </td>
                        <td class="px-3 py-4">
                            <div class="flex items-center gap-1">
                                <a href="/admin/campaigns/{{ $campaign->id }}"
                                   class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50 transition text-xs font-medium">
                                    View
                                </a>
                                <a href="/admin/campaigns/{{ $campaign->id }}/edit"
                                   class="p-1.5 rounded-lg text-gray-600 hover:bg-gray-100 transition text-xs font-medium">
                                    Edit
                                </a>
                                <form action="/admin/campaigns/{{ $campaign->id }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Delete this campaign?')"
                                            class="p-1.5 rounded-lg text-red-500 hover:bg-red-50 transition text-xs font-medium">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-5 border-t border-gray-50">
            {{ $campaigns->links() }}
        </div>
    @endif
</div>
@endsection
