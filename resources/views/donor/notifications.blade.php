@extends('layouts.app')
@section('title', 'Notifications')
@section('content')
<div class="max-w-3xl mx-auto px-6 py-10">
    <h1 class="font-display text-3xl font-bold text-gray-900 mb-2">Notifications</h1>
    <p class="text-gray-500 mb-8">Updates about your registrations and upcoming donations.</p>

    @if($notifications->isEmpty())
        <div class="text-center py-24 bg-white rounded-2xl border border-gray-100 shadow-sm">
            <div class="text-6xl mb-4 opacity-30">🔔</div>
            <h3 class="text-xl font-semibold text-gray-500">No notifications yet</h3>
            <p class="text-gray-400 text-sm mt-2">You'll be notified when your registrations are reviewed.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($notifications as $notification)
            @php $data = $notification->data; @endphp
            <div class="bg-white rounded-2xl shadow-sm border {{ $notification->read_at ? 'border-gray-100' : 'border-blood-200 bg-blood-50' }} p-5 flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
                    {{ $notification->read_at ? 'bg-gray-100' : 'blood-gradient' }}">
                    @switch($data['type'] ?? '')
                        @case('registration_status')
                            <span class="{{ $notification->read_at ? 'text-gray-500' : 'text-white' }} text-lg">
                                {{ ($data['status'] ?? '') === 'accepted' ? '✅' : '❌' }}
                            </span>
                            @break
                        @case('donation_reminder')
                            <span class="{{ $notification->read_at ? 'text-gray-500' : 'text-white' }} text-lg">🔔</span>
                            @break
                        @default
                            <span class="{{ $notification->read_at ? 'text-gray-500' : 'text-white' }} text-lg">📬</span>
                    @endswitch
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-gray-900 text-sm font-medium">
                        {{ $data['message'] ?? 'You have a new notification.' }}
                    </p>
                    @if(isset($data['campaign_id']))
                        <a href="/campaigns/{{ $data['campaign_id'] }}" class="text-blood-600 text-xs hover:underline mt-1 inline-block">
                            View Campaign →
                        </a>
                    @endif
                    <p class="text-gray-400 text-xs mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                </div>
                @if(!$notification->read_at)
                    <span class="w-2.5 h-2.5 blood-gradient rounded-full flex-shrink-0 mt-1"></span>
                @endif
            </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $notifications->links() }}</div>
    @endif
</div>
@endsection
