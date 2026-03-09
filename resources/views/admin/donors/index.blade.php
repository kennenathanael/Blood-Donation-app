@extends('layouts.admin')
@section('title', 'All Donors')
@section('page-title', 'All Donors')
@section('page-subtitle', 'Manage registered blood donors')

@section('content')

{{-- Filter Bar --}}
<form method="GET" action="/admin/donors" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <div class="md:col-span-2">
            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Search</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Name, email or phone..."
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Blood Group</label>
            <select name="blood_group" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blood-300 transition">
                <option value="">All Groups</option>
                @foreach($bloodGroups as $bg)
                    <option value="{{ $bg->id }}" {{ request('blood_group') == $bg->id ? 'selected' : '' }}>
                        {{ $bg->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="blood-gradient text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:opacity-90 transition flex-1">
                🔍 Search
            </button>
            <a href="/admin/donors" class="px-4 py-2.5 rounded-xl text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                Clear
            </a>
        </div>
    </div>
</form>

{{-- Donors Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-5 border-b border-gray-50 flex items-center justify-between">
        <p class="text-sm text-gray-500">
            <strong class="text-gray-800">{{ $donors->total() }}</strong> donor(s) registered
        </p>
    </div>

    @if($donors->isEmpty())
        <div class="text-center py-20 text-gray-400">
            <div class="text-6xl mb-4 opacity-20">👥</div>
            <p class="font-medium">No donors found</p>
            <p class="text-sm mt-1">Try adjusting your search filters.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-5 py-3.5">Donor</th>
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 py-3.5">Blood Group</th>
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 py-3.5">Phone</th>
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 py-3.5">City</th>
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 py-3.5">Registrations</th>
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 py-3.5">Donations</th>
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 py-3.5">Eligible</th>
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 py-3.5">Joined</th>
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 py-3.5">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($donors as $donor)
                    <tr class="hover:bg-gray-50 transition">
                        {{-- Donor Info --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 blood-gradient rounded-xl flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                    {{ $donor->initials }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 text-sm">{{ $donor->name }}</p>
                                    <p class="text-gray-400 text-xs">{{ $donor->email }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Blood Group --}}
                        <td class="px-3 py-4">
                            @if($donor->bloodGroup)
                                <span class="bg-blood-100 text-blood-700 text-xs px-2.5 py-1 rounded-full font-bold">
                                    {{ $donor->bloodGroup->name }}
                                </span>
                            @else
                                <span class="text-gray-300 text-xs italic">Not set</span>
                            @endif
                        </td>

                        {{-- Phone --}}
                        <td class="px-3 py-4 text-gray-500 text-xs">
                            {{ $donor->phone ?? '—' }}
                        </td>

                        {{-- City --}}
                        <td class="px-3 py-4 text-gray-500 text-xs">
                            {{ $donor->city ?? '—' }}
                        </td>

                        {{-- Registrations --}}
                        <td class="px-3 py-4 text-center">
                            <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full font-semibold">
                                {{ $donor->registrations_count }}
                            </span>
                        </td>

                        {{-- Total Donations --}}
                        <td class="px-3 py-4 text-center">
                            <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full font-semibold">
                                {{ $donor->total_donations }}
                            </span>
                        </td>

                        {{-- Eligible --}}
                        <td class="px-3 py-4">
                            @if($donor->is_eligible)
                                <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full font-medium">✓ Yes</span>
                            @else
                                <span class="bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full font-medium">✕ No</span>
                            @endif
                        </td>

                        {{-- Joined --}}
                        <td class="px-3 py-4 text-gray-400 text-xs">
                            {{ $donor->created_at->format('M j, Y') }}
                        </td>

                        {{-- Actions --}}
                        <td class="px-3 py-4">
                            <div class="flex items-center gap-1">
                                <a href="/admin/donors/{{ $donor->id }}"
                                   class="bg-blue-50 text-blue-600 hover:bg-blue-100 px-2.5 py-1.5 rounded-lg text-xs font-medium transition">
                                    View
                                </a>
                                <form action="/admin/donors/{{ $donor->id }}/toggle-eligibility" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="{{ $donor->is_eligible ? 'bg-orange-50 text-orange-600 hover:bg-orange-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }} px-2.5 py-1.5 rounded-lg text-xs font-medium transition">
                                        {{ $donor->is_eligible ? 'Disable' : 'Enable' }}
                                    </button>
                                </form>
                                <form action="/admin/donors/{{ $donor->id }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Remove {{ $donor->name }} from the system?')"
                                            class="bg-red-50 text-red-500 hover:bg-red-100 px-2.5 py-1.5 rounded-lg text-xs font-medium transition">
                                        Remove
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-5 border-t border-gray-50">
            {{ $donors->links() }}
        </div>
    @endif
</div>
@endsection
