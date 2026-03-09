{{-- resources/views/donor/history.blade.php --}}
@extends('layouts.app')
@section('title', 'Donation History')
@section('content')
<div class="max-w-4xl mx-auto px-6 py-10">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-display text-3xl font-bold text-gray-900">Donation History</h1>
            <p class="text-gray-500 mt-1">You have donated blood <strong class="text-blood-600">{{ $totalDonations }}</strong> time(s). Thank you! 🩸</p>
        </div>
        @if($totalDonations > 0)
            <div class="bg-blood-50 border border-blood-100 rounded-2xl px-6 py-4 text-center">
                <p class="font-display text-4xl font-bold text-blood-700">{{ $totalDonations }}</p>
                <p class="text-blood-500 text-xs font-medium">Lives Helped</p>
            </div>
        @endif
    </div>

    @if($donations->isEmpty())
        <div class="text-center py-24 bg-white rounded-2xl border border-gray-100 shadow-sm">
            <div class="text-6xl mb-4 opacity-30">🩸</div>
            <h3 class="text-xl font-semibold text-gray-500">No donations yet</h3>
            <p class="text-gray-400 mt-2 text-sm">Register for a campaign and make your first donation!</p>
            <a href="/campaigns" class="mt-6 inline-block blood-gradient text-white px-6 py-3 rounded-xl font-semibold hover:opacity-90 transition">
                Find a Campaign →
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($donations as $donation)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-5">
                <div class="w-14 h-14 blood-gradient rounded-2xl flex items-center justify-center flex-shrink-0 shadow">
                    <span class="text-white text-2xl">🩸</span>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900">{{ $donation->campaign->title }}</h3>
                    <p class="text-gray-500 text-sm mt-0.5">📍 {{ $donation->campaign->location }}</p>
                    <p class="text-gray-400 text-xs mt-1">
                        Donated on: <strong class="text-gray-600">{{ $donation->donated_at->format('F j, Y') }}</strong>
                    </p>
                </div>
                <div class="text-right flex-shrink-0">
                    <span class="status-donated text-xs px-3 py-1.5 rounded-full font-semibold">Donated ✓</span>
                    <p class="text-xs text-gray-400 mt-2">{{ $donation->donated_at->diffForHumans() }}</p>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $donations->links() }}</div>
    @endif
</div>
@endsection
