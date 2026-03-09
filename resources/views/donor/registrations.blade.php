{{-- ============================================================ --}}
{{-- resources/views/donor/registrations.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.app')
@section('title', 'My Registrations')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-10">
    <h1 class="font-display text-3xl font-bold text-gray-900 mb-2">My Registrations</h1>
    <p class="text-gray-500 mb-8">Track all your campaign registration applications.</p>

    @if($registrations->isEmpty())
        <div class="text-center py-24 bg-white rounded-2xl border border-gray-100 shadow-sm">
            <div class="text-6xl mb-4 opacity-30">📋</div>
            <h3 class="text-xl font-semibold text-gray-500">No registrations yet</h3>
            <p class="text-gray-400 text-sm mt-2">Browse campaigns and register to donate blood.</p>
            <a href="/campaigns" class="mt-6 inline-block blood-gradient text-white px-6 py-3 rounded-xl font-semibold hover:opacity-90 transition">
                Browse Campaigns →
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($registrations as $reg)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 blood-gradient rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-xl">🩸</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 text-base">{{ $reg->campaign->title }}</h3>
                            <p class="text-gray-500 text-sm mt-0.5">
                                📍 {{ $reg->campaign->location }}
                                · 📅 {{ $reg->campaign->campaign_date->format('M j, Y') }}
                            </p>
                            <p class="text-gray-400 text-xs mt-1">
                                Registered: {{ $reg->registered_at->format('M j, Y H:i') }}
                                ({{ $reg->registered_at->diffForHumans() }})
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="status-{{ $reg->status }} text-xs px-3 py-1.5 rounded-full font-semibold">
                            {{ $reg->statusLabel() }}
                        </span>
                        <a href="/campaigns/{{ $reg->campaign->id }}" class="text-xs text-blood-600 hover:underline">
                            View →
                        </a>
                    </div>
                </div>

                @if($reg->health_notes)
                    <div class="mt-4 pt-4 border-t border-gray-50">
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Your Notes</p>
                        <p class="text-gray-600 text-sm">{{ $reg->health_notes }}</p>
                    </div>
                @endif

                @if(in_array($reg->status, ['pending', 'accepted']))
                    <div class="mt-4 pt-4 border-t border-gray-50 flex justify-end">
                        <form action="/registrations/{{ $reg->id }}/cancel" method="POST">
                            @csrf
                            <button type="submit"
                                    onclick="return confirm('Cancel this registration?')"
                                    class="text-xs text-red-500 hover:text-red-700 border border-red-200 px-3 py-1.5 rounded-lg hover:bg-red-50 transition">
                                Cancel Registration
                            </button>
                        </form>
                    </div>
                @endif
            </div>
            @endforeach
        </div>

        <div class="mt-6">{{ $registrations->links() }}</div>
    @endif
</div>
@endsection
