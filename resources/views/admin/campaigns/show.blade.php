@extends('layouts.admin')
@section('title', $campaign->title)
@section('page-title', $campaign->title)
@section('page-subtitle', 'Manage campaign registrations and donors')

@section('content')

{{-- Campaign Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    @foreach([
        ['label' => 'Total Registrations', 'value' => $campaign->registrations_count,          'color' => 'gray'],
        ['label' => 'Pending',             'value' => $campaign->pending_registrations_count,  'color' => 'yellow'],
        ['label' => 'Accepted',            'value' => $campaign->accepted_registrations_count, 'color' => 'green'],
        ['label' => 'Donated',             'value' => $campaign->completed_donations_count,    'color' => 'blue'],
        ['label' => 'Spots Left',          'value' => $campaign->spotsRemaining(),             'color' => 'blood'],
    ] as $s)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
        <p class="text-xl font-bold text-gray-900">{{ $s['value'] }}</p>
        <p class="text-xs text-gray-400 mt-0.5">{{ $s['label'] }}</p>
    </div>
    @endforeach
</div>

{{-- Campaign Actions --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <span class="status-{{ $campaign->status }} text-sm px-3 py-1.5 rounded-full font-semibold">
                {{ ucfirst($campaign->status) }}
            </span>
            <span class="text-gray-500 text-sm">
                📅 {{ $campaign->campaign_date->format('D, M j, Y \a\t H:i') }}
                · 📍 {{ $campaign->location }}
            </span>
        </div>
        <div class="flex gap-2 flex-wrap">
            {{-- Send Notification --}}
            <form action="/admin/campaigns/{{ $campaign->id }}/notify" method="POST" class="inline">
                @csrf
                <button type="submit"
                        onclick="return confirm('Send reminder email to all {{ $campaign->accepted_registrations_count }} accepted donors?')"
                        class="bg-blue-600 text-white px-4 py-2 rounded-xl text-xs font-semibold hover:bg-blue-700 transition">
                    🔔 Notify Donors ({{ $campaign->accepted_registrations_count }})
                </button>
            </form>
            {{-- Export --}}
            <a href="/admin/campaigns/{{ $campaign->id }}/export"
               class="bg-green-600 text-white px-4 py-2 rounded-xl text-xs font-semibold hover:bg-green-700 transition">
                📥 Export CSV
            </a>
            {{-- Edit --}}
            <a href="/admin/campaigns/{{ $campaign->id }}/edit"
               class="bg-gray-100 text-gray-700 px-4 py-2 rounded-xl text-xs font-semibold hover:bg-gray-200 transition">
                ✏️ Edit
            </a>
        </div>
    </div>
</div>

{{-- Registrations Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-5 border-b border-gray-50">
        <form method="GET" action="/admin/campaigns/{{ $campaign->id }}" class="flex flex-wrap gap-3 items-end">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search donor name/email..."
                       class="border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition w-64">
            </div>
            <div>
                <select name="status" class="border border-gray-200 rounded-xl px-4 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blood-300 transition">
                    <option value="">All Statuses</option>
                    <option value="pending"   {{ request('status') == 'pending'   ? 'selected':'' }}>Pending</option>
                    <option value="accepted"  {{ request('status') == 'accepted'  ? 'selected':'' }}>Accepted</option>
                    <option value="rejected"  {{ request('status') == 'rejected'  ? 'selected':'' }}>Rejected</option>
                    <option value="donated"   {{ request('status') == 'donated'   ? 'selected':'' }}>Donated</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected':'' }}>Cancelled</option>
                </select>
            </div>
            <button type="submit" class="blood-gradient text-white px-4 py-2 rounded-xl text-sm font-semibold hover:opacity-90 transition">Filter</button>
            <a href="/admin/campaigns/{{ $campaign->id }}" class="px-4 py-2 rounded-xl text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 transition">Clear</a>
        </form>
    </div>

    @if($registrations->isEmpty())
        <div class="text-center py-16 text-gray-400">
            <p class="text-4xl mb-3 opacity-30">👥</p>
            <p class="text-sm">No registrations found.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-5 py-3.5">Donor</th>
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 py-3.5">Blood</th>
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 py-3.5">Notes</th>
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 py-3.5">Registered</th>
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 py-3.5">Status</th>
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 py-3.5">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($registrations as $reg)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 blood-gradient rounded-xl flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                    {{ substr($reg->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $reg->user->name }}</p>
                                    <p class="text-gray-400 text-xs">{{ $reg->user->email }}</p>
                                    @if($reg->user->phone)
                                        <p class="text-gray-400 text-xs">{{ $reg->user->phone }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-3 py-4">
                            @if($reg->user->bloodGroup)
                                <span class="bg-blood-100 text-blood-700 text-xs px-2 py-1 rounded font-bold">
                                    {{ $reg->user->bloodGroup->name }}
                                </span>
                            @else
                                <span class="text-gray-300 text-xs">N/A</span>
                            @endif
                        </td>
                        <td class="px-3 py-4 max-w-xs">
                            @if($reg->health_notes)
                                <p class="text-gray-500 text-xs truncate" title="{{ $reg->health_notes }}">
                                    {{ Str::limit($reg->health_notes, 40) }}
                                </p>
                            @else
                                <span class="text-gray-300 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-3 py-4 text-gray-400 text-xs">
                            {{ $reg->registered_at->format('M j, Y') }}
                        </td>
                        <td class="px-3 py-4">
                            <span class="status-{{ $reg->status }} text-xs px-2.5 py-1 rounded-full font-medium">
                                {{ $reg->statusLabel() }}
                            </span>
                        </td>
                        <td class="px-3 py-4">
                            <div class="flex items-center gap-1">
                                {{-- Accept --}}
                                @if($reg->status === 'pending')
                                <form action="/admin/registrations/{{ $reg->id }}/accept" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="bg-green-100 text-green-700 hover:bg-green-200 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition">
                                        ✓ Accept
                                    </button>
                                </form>
                                <form action="/admin/registrations/{{ $reg->id }}/reject" method="POST">
                                    @csrf
                                    <input type="hidden" name="admin_notes" value="">
                                    <button type="submit"
                                            class="bg-red-100 text-red-600 hover:bg-red-200 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition">
                                        ✕ Reject
                                    </button>
                                </form>
                                @endif

                                {{-- Mark Donated --}}
                                @if($reg->status === 'accepted')
                                <form action="/admin/registrations/{{ $reg->id }}/donated" method="POST">
                                    @csrf
                                    <button type="submit"
                                            onclick="return confirm('Mark {{ $reg->user->name }} as donated?')"
                                            class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition">
                                        🩸 Donated
                                    </button>
                                </form>
                                @endif

                                {{-- Donated --}}
                                @if($reg->status === 'donated')
                                    <span class="text-gray-400 text-xs">
                                        ✓ {{ $reg->donated_at?->format('M j') }}
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-5 border-t border-gray-50">
            {{ $registrations->links() }}
        </div>
    @endif
</div>
@endsection
