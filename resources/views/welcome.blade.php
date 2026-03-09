@extends('layouts.app')
@section('title', 'Home — Save Lives')

@section('content')

{{-- ── HERO ── --}}
<section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-blood-900 to-gray-900 text-white min-h-[90vh] flex items-center">
    {{-- Background pattern --}}
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 left-20 w-72 h-72 bg-blood-500 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-20 w-96 h-96 bg-blood-700 rounded-full blur-3xl"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-6 py-24 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <div class="animate-fadeInUp">
            <span class="inline-block bg-blood-600/30 border border-blood-500/40 text-blood-300 text-xs font-medium px-4 py-1.5 rounded-full mb-6 uppercase tracking-wider">
                🩸 Hospital Blood Donation Program
            </span>
            <h1 class="font-display text-5xl md:text-6xl lg:text-7xl font-bold leading-tight mb-6">
                Give Blood.<br>
                <span class="text-blood-400">Save Lives.</span>
            </h1>
            <p class="text-gray-300 text-lg leading-relaxed mb-8 max-w-lg">
                Join our hospital's blood donation campaigns and become a hero for patients in need. One donation can save up to three lives.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="/campaigns" class="blood-gradient px-8 py-4 rounded-xl font-semibold text-white shadow-lg hover:opacity-90 transition text-base">
                    🩸 View Campaigns
                </a>
                <a href="/register" class="bg-white/10 border border-white/20 px-8 py-4 rounded-xl font-semibold text-white hover:bg-white/20 transition text-base backdrop-blur-sm">
                    Become a Donor
                </a>
            </div>

            {{-- Stats row --}}
            <div class="flex gap-8 mt-12 pt-8 border-t border-white/10">
                <div>
                    <p class="text-3xl font-display font-bold text-blood-400">500+</p>
                    <p class="text-gray-400 text-sm">Active Donors</p>
                </div>
                <div>
                    <p class="text-3xl font-display font-bold text-blood-400">50+</p>
                    <p class="text-gray-400 text-sm">Campaigns</p>
                </div>
                <div>
                    <p class="text-3xl font-display font-bold text-blood-400">1,200+</p>
                    <p class="text-gray-400 text-sm">Lives Saved</p>
                </div>
            </div>
        </div>

        {{-- Right side illustration --}}
        <div class="hidden lg:flex items-center justify-center">
            <div class="relative">
                <div class="w-80 h-80 blood-gradient rounded-full opacity-20 blur-2xl absolute inset-0 m-auto"></div>
                <div class="relative bg-white/5 border border-white/10 rounded-3xl p-10 backdrop-blur-sm text-center">
                    <div class="text-9xl mb-4">🩸</div>
                    <p class="text-white/80 text-sm font-medium">Every 2 seconds,<br>someone needs blood.</p>
                    <p class="text-blood-400 font-semibold mt-2">Be the reason they survive.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Scroll hint --}}
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <svg class="w-6 h-6 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </div>
</section>

{{-- ── HOW IT WORKS ── --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-14">
            <span class="text-blood-600 text-sm font-semibold uppercase tracking-wider">Simple Process</span>
            <h2 class="font-display text-4xl font-bold text-gray-900 mt-2">How It Works</h2>
            <p class="text-gray-500 mt-3 max-w-md mx-auto">Join a campaign in 4 easy steps and make a life-saving difference.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            @foreach([
                ['1', '📝', 'Register', 'Create your free donor account with your details and blood group.'],
                ['2', '🔍', 'Find a Campaign', 'Browse active hospital blood donation campaigns near you.'],
                ['3', '✅', 'Get Accepted', 'Apply and wait for hospital confirmation via email notification.'],
                ['4', '🩸', 'Donate & Save', 'Show up on the day and make your life-saving donation!'],
            ] as [$num, $icon, $title, $desc])
            <div class="text-center group">
                <div class="relative inline-flex items-center justify-center w-20 h-20 blood-gradient rounded-2xl shadow-lg mb-5 group-hover:scale-110 transition">
                    <span class="text-3xl">{{ $icon }}</span>
                    <div class="absolute -top-2 -right-2 w-7 h-7 bg-white border-2 border-blood-200 rounded-full flex items-center justify-center">
                        <span class="text-blood-700 text-xs font-bold">{{ $num }}</span>
                    </div>
                </div>
                <h3 class="font-semibold text-gray-900 text-lg mb-2">{{ $title }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── ACTIVE CAMPAIGNS PREVIEW ── --}}
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-end justify-between mb-12">
            <div>
                <span class="text-blood-600 text-sm font-semibold uppercase tracking-wider">Donate Now</span>
                <h2 class="font-display text-4xl font-bold text-gray-900 mt-2">Active Campaigns</h2>
            </div>
            <a href="/campaigns" class="text-blood-600 hover:text-blood-800 font-medium text-sm flex items-center gap-1">
                View all campaigns <span>→</span>
            </a>
        </div>

        @php
            $campaigns = \App\Models\Campaign::where('status', 'active')
                ->withCount('acceptedRegistrations')
                ->orderBy('campaign_date')
                ->take(3)
                ->get();
        @endphp

        @if($campaigns->isEmpty())
            <div class="text-center py-16 text-gray-400">
                <div class="text-5xl mb-4">📅</div>
                <p class="font-medium">No active campaigns right now.</p>
                <p class="text-sm mt-1">Check back soon for upcoming donation drives.</p>
            </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($campaigns as $campaign)
            <a href="/campaigns/{{ $campaign->id }}" class="block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden card-hover group">
                <div class="blood-gradient h-2"></div>
                @if($campaign->banner_image)
                    <img src="{{ asset('storage/' . $campaign->banner_image) }}" alt="{{ $campaign->title }}" class="w-full h-44 object-cover">
                @else
                    <div class="w-full h-44 bg-gradient-to-br from-blood-50 to-blood-100 flex items-center justify-center">
                        <span class="text-6xl opacity-40">🩸</span>
                    </div>
                @endif
                <div class="p-5">
                    <span class="status-active text-xs px-2.5 py-1 rounded-full font-medium">Active</span>
                    <h3 class="font-semibold text-gray-900 text-lg mt-2 group-hover:text-blood-700 transition">{{ $campaign->title }}</h3>
                    <p class="text-gray-500 text-sm mt-1">📍 {{ $campaign->location }}</p>
                    <p class="text-gray-500 text-sm">📅 {{ $campaign->campaign_date->format('D, M j, Y') }}</p>
                    <div class="mt-4">
                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                            <span>{{ $campaign->accepted_registrations_count }} donors</span>
                            <span>{{ $campaign->max_donors }} max</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="blood-gradient h-2 rounded-full" style="width: {{ $campaign->fillPercentage() }}%"></div>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="text-blood-600 font-semibold text-sm">{{ $campaign->spotsRemaining() }} spots left</span>
                        <span class="text-blood-600 text-sm font-medium group-hover:translate-x-1 transition">Register →</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>
</section>

{{-- ── WHY DONATE ── --}}
<section class="py-20 bg-white" id="about">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <span class="text-blood-600 text-sm font-semibold uppercase tracking-wider">Why It Matters</span>
                <h2 class="font-display text-4xl font-bold text-gray-900 mt-2 mb-6">Blood Cannot Be<br>Manufactured</h2>
                <p class="text-gray-600 leading-relaxed mb-6">
                    Blood is a critical resource that can only come from human donors. Hospitals constantly need all blood types to help patients undergoing surgery, accident victims, cancer patients, and many others.
                </p>
                <div class="space-y-4">
                    @foreach([
                        ['🩸', 'Every 2 seconds someone needs blood', 'Hospitals need a constant, reliable supply to treat emergency patients.'],
                        ['💪', 'One donation saves up to 3 lives', 'Your blood is separated into red cells, plasma, and platelets — each helping different patients.'],
                        ['⏱️', 'Takes only 30–60 minutes', 'The actual blood collection takes just 8–10 minutes. Total visit is under an hour.'],
                    ] as [$icon, $title, $desc])
                    <div class="flex gap-4">
                        <div class="w-12 h-12 blood-gradient rounded-xl flex items-center justify-center flex-shrink-0 shadow">
                            <span class="text-xl">{{ $icon }}</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ $title }}</h4>
                            <p class="text-gray-500 text-sm mt-0.5">{{ $desc }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="bg-gradient-to-br from-blood-50 to-blood-100 rounded-3xl p-10">
                <h3 class="font-display text-2xl font-bold text-blood-800 mb-6">Who Can Donate?</h3>
                <ul class="space-y-3">
                    @foreach([
                        '✅ Age 18–65 years old',
                        '✅ Weight above 50 kg',
                        '✅ Healthy and feeling well on donation day',
                        '✅ No major illness in past 6 months',
                        '✅ At least 56 days since last donation',
                        '✅ No recent tattoos or piercings (3 months)',
                    ] as $item)
                    <li class="flex items-center gap-2 text-blood-900 text-sm">
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
                <a href="/register" class="mt-8 inline-block blood-gradient text-white px-6 py-3 rounded-xl font-semibold hover:opacity-90 transition shadow">
                    Register as Donor →
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ── CTA BANNER ── --}}
<section class="blood-gradient text-white py-20">
    <div class="max-w-4xl mx-auto px-6 text-center">
        <div class="text-6xl mb-6">❤️</div>
        <h2 class="font-display text-4xl font-bold mb-4">Ready to Save a Life Today?</h2>
        <p class="text-white/80 text-lg mb-8">Join hundreds of donors who have already made a difference. Your blood is someone's lifeline.</p>
        <div class="flex gap-4 justify-center flex-wrap">
            <a href="/register" class="bg-white text-blood-700 px-8 py-4 rounded-xl font-bold hover:bg-gray-50 transition shadow-lg">
                Register Now — It's Free
            </a>
            <a href="/campaigns" class="bg-white/20 border border-white/30 text-white px-8 py-4 rounded-xl font-semibold hover:bg-white/30 transition">
                Browse Campaigns
            </a>
        </div>
    </div>
</section>

@endsection
