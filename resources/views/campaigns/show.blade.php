@extends('layouts.app')
@section('title', $campaign->title)

@section('content')
<div class="max-w-6xl mx-auto px-6 py-10">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="/" class="hover:text-blood-600">Home</a>
        <span>/</span>
        <a href="/campaigns" class="hover:text-blood-600">Campaigns</a>
        <span>/</span>
        <span class="text-gray-600 font-medium">{{ $campaign->title }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- LEFT: Campaign Details --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Banner --}}
            @if($campaign->banner_image)
                <img src="{{ asset('storage/' . $campaign->banner_image) }}"
                     alt="{{ $campaign->title }}"
                     class="w-full h-64 object-cover rounded-2xl shadow-sm">
            @else
                <div class="w-full h-64 bg-gradient-to-br from-blood-100 to-blood-200 rounded-2xl flex items-center justify-center">
                    <span class="text-8xl opacity-30">🩸</span>
                </div>
            @endif

            {{-- Title & Status --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-7">
                <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
                    <div>
                        <span class="status-{{ $campaign->status }} text-xs px-3 py-1.5 rounded-full font-medium">
                            {{ ucfirst($campaign->status) }}
                        </span>
                        <h1 class="font-display text-3xl font-bold text-gray-900 mt-3">{{ $campaign->title }}</h1>
                    </div>
                    @if($campaign->daysUntil() > 0)
                        <div class="text-center bg-blood-50 border border-blood-100 rounded-xl px-5 py-3">
                            <p class="font-display text-3xl font-bold text-blood-700">{{ $campaign->daysUntil() }}</p>
                            <p class="text-blood-500 text-xs font-medium">Days Away</p>
                        </div>
                    @endif
                </div>

                @if($campaign->description)
                    <p class="text-gray-600 leading-relaxed">{{ $campaign->description }}</p>
                @endif
            </div>

            {{-- Campaign Info Grid --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-7">
                <h3 class="font-semibold text-gray-900 mb-5 text-lg">Campaign Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 blood-gradient rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-lg">📍</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Location</p>
                            <p class="text-gray-800 font-medium">{{ $campaign->location }}</p>
                            @if($campaign->address)
                                <p class="text-gray-500 text-sm">{{ $campaign->address }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 blood-gradient rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-lg">📅</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Campaign Date</p>
                            <p class="text-gray-800 font-medium">{{ $campaign->campaign_date->format('l, F j, Y') }}</p>
                            <p class="text-gray-500 text-sm">{{ $campaign->campaign_date->format('H:i') }}
                                @if($campaign->end_time) – {{ $campaign->end_time->format('H:i') }}@endif
                            </p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-yellow-500 rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-lg">⏰</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Registration Deadline</p>
                            <p class="text-gray-800 font-medium">{{ $campaign->registration_deadline->format('F j, Y') }}</p>
                            <p class="text-gray-500 text-sm">{{ $campaign->registration_deadline->format('H:i') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-lg">👥</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Donors</p>
                            <p class="text-gray-800 font-medium">{{ $campaign->accepted_registrations_count }} accepted</p>
                            <p class="text-gray-500 text-sm">{{ $campaign->spotsRemaining() }} spots remaining</p>
                        </div>
                    </div>
                    @if($campaign->contact_phone || $campaign->contact_email)
                    <div class="flex items-start gap-3 md:col-span-2">
                        <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-lg">📞</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Contact</p>
                            @if($campaign->contact_phone)
                                <p class="text-gray-800 font-medium">{{ $campaign->contact_phone }}</p>
                            @endif
                            @if($campaign->contact_email)
                                <p class="text-gray-500 text-sm">{{ $campaign->contact_email }}</p>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Capacity bar --}}
                <div class="mt-6 pt-6 border-t border-gray-50">
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span><strong>{{ $campaign->accepted_registrations_count }}</strong> / {{ $campaign->max_donors }} donors accepted</span>
                        <span class="font-semibold text-blood-600">{{ $campaign->fillPercentage() }}% full</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3">
                        <div class="blood-gradient h-3 rounded-full transition-all duration-500"
                             style="width: {{ $campaign->fillPercentage() }}%"></div>
                    </div>
                </div>
            </div>

            {{-- Requirements & Benefits --}}
            @if($campaign->requirements || $campaign->benefits)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @if($campaign->requirements)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                        <span class="text-blood-500">📋</span> Requirements
                    </h3>
                    <p class="text-gray-600 text-sm leading-relaxed whitespace-pre-line">{{ $campaign->requirements }}</p>
                </div>
                @endif
                @if($campaign->benefits)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                        <span class="text-green-500">🎁</span> What You Get
                    </h3>
                    <p class="text-gray-600 text-sm leading-relaxed whitespace-pre-line">{{ $campaign->benefits }}</p>
                </div>
                @endif
            </div>
            @endif
        </div>

        {{-- RIGHT: Registration Sidebar --}}
        <div class="lg:col-span-1 space-y-5">

            {{-- Registration Status for logged-in donor --}}
            @auth
                @if(Auth::user()->isDonor())
                    @if($myRegistration)
                        {{-- Already registered --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
                            <div class="w-16 h-16 mx-auto rounded-full status-{{ $myRegistration->status }} flex items-center justify-center text-2xl mb-4">
                                @switch($myRegistration->status)
                                    @case('pending')   ⏳ @break
                                    @case('accepted')  ✅ @break
                                    @case('rejected')  ❌ @break
                                    @case('donated')   🩸 @break
                                    @case('cancelled') 🚫 @break
                                @endswitch
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-1">Your Registration</h3>
                            <span class="status-{{ $myRegistration->status }} text-sm px-3 py-1.5 rounded-full font-medium">
                                {{ $myRegistration->statusLabel() }}
                            </span>
                            <p class="text-gray-400 text-xs mt-3">
                                Registered {{ $myRegistration->registered_at->diffForHumans() }}
                            </p>
                            @if(in_array($myRegistration->status, ['pending', 'accepted']))
                                <form action="/registrations/{{ $myRegistration->id }}/cancel" method="POST" class="mt-4">
                                    @csrf
                                    <button type="submit"
                                            onclick="return confirm('Are you sure you want to cancel your registration?')"
                                            class="w-full border border-red-300 text-red-600 py-2 rounded-xl text-sm font-medium hover:bg-red-50 transition">
                                        Cancel Registration
                                    </button>
                                </form>
                            @endif
                        </div>
                    @elseif($campaign->isOpen())
                        {{-- Register form --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <div class="blood-gradient -mx-6 -mt-6 px-6 pt-6 pb-5 rounded-t-2xl mb-5 text-white text-center">
                                <span class="text-3xl">🩸</span>
                                <h3 class="font-semibold text-lg mt-1">Register to Donate</h3>
                                <p class="text-white/70 text-xs mt-1">{{ $campaign->spotsRemaining() }} spots available</p>
                            </div>

                            @if($errors->any())
                            <div class="bg-red-50 border border-red-200 rounded-xl p-3 mb-4">
                                @foreach($errors->all() as $error)
                                    <p class="text-red-600 text-xs">• {{ $error }}</p>
                                @endforeach
                            </div>
                            @endif

                            <form action="/campaigns/{{ $campaign->id }}/register" method="POST" class="space-y-4">
                                @csrf

                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Blood Group <span class="text-blood-500">*</span></label>
                                    <select name="blood_group_id" required
                                            class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blood-300 transition">
                                        <option value="">Select blood group</option>
                                        @foreach($bloodGroups as $bg)
                                            <option value="{{ $bg->id }}" {{ Auth::user()->blood_group_id == $bg->id ? 'selected' : '' }}>
                                                {{ $bg->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Health Notes</label>
                                    <textarea name="health_notes" rows="3"
                                              class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition resize-none"
                                              placeholder="Any medical conditions, medications, or health info we should know...">{{ old('health_notes') }}</textarea>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Emergency Contact</label>
                                    <input type="text" name="emergency_contact"
                                           class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition"
                                           placeholder="Full name">
                                    <input type="tel" name="emergency_phone"
                                           class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blood-300 transition mt-2"
                                           placeholder="Phone number">
                                </div>

                                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                                    <input type="checkbox" name="has_donated_before" value="1" class="rounded">
                                    I have donated blood before
                                </label>

                                <button type="submit"
                                        class="w-full blood-gradient text-white py-3.5 rounded-xl font-semibold text-sm hover:opacity-90 transition shadow-lg">
                                    🩸 Submit Registration
                                </button>

                                <p class="text-xs text-gray-400 text-center">
                                    Your registration will be reviewed by the hospital team.
                                </p>
                            </form>
                        </div>
                    @else
                        {{-- Campaign not open --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
                            <div class="text-4xl mb-3">🔒</div>
                            <h3 class="font-semibold text-gray-700">Registration Closed</h3>
                            <p class="text-gray-400 text-sm mt-2">
                                @if($campaign->status === 'completed')
                                    This campaign has been completed.
                                @elseif($campaign->status === 'cancelled')
                                    This campaign was cancelled.
                                @elseif(now()->greaterThan($campaign->registration_deadline))
                                    The registration deadline has passed.
                                @else
                                    No more spots available.
                                @endif
                            </p>
                            <a href="/campaigns" class="mt-4 inline-block text-blood-600 text-sm hover:underline">
                                Browse other campaigns →
                            </a>
                        </div>
                    @endif
                @endif
            @endauth

            @guest
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
                <div class="text-4xl mb-3">🩸</div>
                <h3 class="font-semibold text-gray-900 mb-2">Want to Donate?</h3>
                <p class="text-gray-500 text-sm mb-5">Create a free account to register for this campaign.</p>
                <a href="/register" class="block blood-gradient text-white py-3 rounded-xl font-semibold text-sm hover:opacity-90 transition mb-2">
                    Register as Donor
                </a>
                <a href="/login" class="block border border-gray-200 text-gray-600 py-3 rounded-xl text-sm hover:bg-gray-50 transition">
                    Already have an account? Login
                </a>
            </div>
            @endguest

            {{-- Campaign Summary Box --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h4 class="text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wider">Campaign Summary</h4>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Total Spots</span>
                        <span class="font-medium text-gray-800">{{ $campaign->max_donors }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Accepted</span>
                        <span class="font-medium text-green-600">{{ $campaign->accepted_registrations_count }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Total Applicants</span>
                        <span class="font-medium text-gray-800">{{ $campaign->registrations_count }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Donations Made</span>
                        <span class="font-medium text-blood-600">{{ $campaign->completed_donations_count }}</span>
                    </div>
                    <div class="flex justify-between pt-3 border-t border-gray-50">
                        <span class="text-gray-500">Available Spots</span>
                        <span class="font-bold text-blood-600">{{ $campaign->spotsRemaining() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
