<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BloodLife') — Hospital Blood Donation</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=DM+Sans:wght@300;400;500;600&family=DM+Mono&display=swap" rel="stylesheet">

    <!-- Tailwind CDN (use compiled in production) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        blood: {
                            50:  '#fff1f1',
                            100: '#ffe0e0',
                            200: '#ffc5c5',
                            300: '#ff9d9d',
                            400: '#ff6464',
                            500: '#ff2d2d',
                            600: '#e81010',
                            700: '#c30a0a',
                            800: '#a10d0d',
                            900: '#850f0f',
                        }
                    },
                    fontFamily: {
                        display: ['Playfair Display', 'serif'],
                        body:    ['DM Sans', 'sans-serif'],
                        mono:    ['DM Mono', 'monospace'],
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
        .blood-gradient { background: linear-gradient(135deg, #c30a0a 0%, #e81010 60%, #ff2d2d 100%); }
        .card-hover { transition: transform 0.2s, box-shadow 0.2s; }
        .card-hover:hover { transform: translateY(-3px); box-shadow: 0 12px 32px rgba(193,10,10,0.15); }
        .nav-link { transition: color 0.15s, background 0.15s; }
        .status-pending   { background:#fef9c3; color:#854d0e; border:1px solid #fde047; }
        .status-accepted  { background:#dcfce7; color:#166534; border:1px solid #86efac; }
        .status-rejected  { background:#fee2e2; color:#991b1b; border:1px solid #fca5a5; }
        .status-donated   { background:#dbeafe; color:#1e40af; border:1px solid #93c5fd; }
        .status-cancelled { background:#f3f4f6; color:#374151; border:1px solid #d1d5db; }
        .status-active    { background:#dcfce7; color:#166534; border:1px solid #86efac; }
        .status-upcoming  { background:#dbeafe; color:#1e40af; border:1px solid #93c5fd; }
        .status-completed { background:#f3f4f6; color:#374151; border:1px solid #d1d5db; }
        .status-cancelled { background:#fee2e2; color:#991b1b; border:1px solid #fca5a5; }
        @keyframes fadeInUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        .animate-fadeInUp { animation: fadeInUp 0.5s ease forwards; }
        .progress-bar-fill { transition: width 1s ease; }
        .sidebar-link.active { background:rgba(255,255,255,0.15); border-left:3px solid #fff; }
        .sidebar-link { border-left:3px solid transparent; }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- ── NAVBAR ── -->
    <nav class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                <!-- Logo -->
                <a href="/" class="flex items-center gap-2 group">
                    <div class="w-9 h-9 blood-gradient rounded-lg flex items-center justify-center shadow-md group-hover:shadow-lg transition">
                        <span class="text-white text-lg">🩸</span>
                    </div>
                    <span class="font-display text-xl text-blood-700 font-bold">BloodLife</span>
                </a>

                <!-- Center Nav Links -->
                <div class="hidden md:flex items-center gap-1">
                    <a href="/" class="nav-link px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-blood-700 hover:bg-blood-50">
                        Home
                    </a>
                    <a href="/campaigns" class="nav-link px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-blood-700 hover:bg-blood-50">
                        Campaigns
                    </a>
                    <a href="#about" class="nav-link px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-blood-700 hover:bg-blood-50">
                        About
                    </a>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-3">
                    @guest
                        <a href="/login" class="text-sm font-medium text-gray-600 hover:text-blood-700 transition">Login</a>
                        <a href="/register" class="blood-gradient text-white text-sm font-medium px-4 py-2 rounded-lg shadow hover:opacity-90 transition">
                            Register
                        </a>
                    @endguest

                    @auth
                        @if(Auth::user()->isAdmin())
                            <a href="/admin/dashboard" class="nav-link px-3 py-2 rounded-lg text-sm font-medium text-blood-700 bg-blood-50 hover:bg-blood-100">
                                ⚙️ Admin Panel
                            </a>
                        @else
                            <!-- Notification Bell -->
                            <a href="/donor/notifications" class="relative p-2 rounded-lg hover:bg-gray-100 transition">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                @if(Auth::user()->unreadNotifications->count() > 0)
                                    <span class="absolute top-1 right-1 w-4 h-4 blood-gradient rounded-full text-white text-xs flex items-center justify-center">
                                        {{ Auth::user()->unreadNotifications->count() }}
                                    </span>
                                @endif
                            </a>

                            <!-- Donor Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-gray-100 transition">
                                    <div class="w-8 h-8 blood-gradient rounded-full flex items-center justify-center text-white text-xs font-bold shadow">
                                        {{ Auth::user()->initials }}
                                    </div>
                                    <span class="hidden md:block text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false"
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                                    <a href="/donor/dashboard" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                                        <span>🏠</span> Dashboard
                                    </a>
                                    <a href="/donor/profile" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                                        <span>👤</span> My Profile
                                    </a>
                                    <a href="/donor/registrations" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                                        <span>📋</span> My Registrations
                                    </a>
                                    <a href="/donor/history" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                                        <span>🩸</span> Donation History
                                    </a>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <form action="/logout" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50">
                                            <span>🚪</span> Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- ── FLASH MESSAGES ── -->
    @if(session('success'))
        <div id="flash-success" class="fixed top-20 right-4 z-50 max-w-md animate-fadeInUp">
            <div class="bg-green-50 border border-green-200 text-green-800 px-5 py-3.5 rounded-xl shadow-lg flex items-start gap-3">
                <span class="text-green-500 text-lg mt-0.5">✓</span>
                <div>
                    <p class="font-medium text-sm">{{ session('success') }}</p>
                </div>
                <button onclick="document.getElementById('flash-success').remove()" class="ml-auto text-green-400 hover:text-green-600">✕</button>
            </div>
        </div>
        <script>setTimeout(() => { const el = document.getElementById('flash-success'); if(el) el.remove(); }, 5000);</script>
    @endif

    @if(session('error'))
        <div id="flash-error" class="fixed top-20 right-4 z-50 max-w-md animate-fadeInUp">
            <div class="bg-red-50 border border-red-200 text-red-800 px-5 py-3.5 rounded-xl shadow-lg flex items-start gap-3">
                <span class="text-red-500 text-lg mt-0.5">✕</span>
                <p class="font-medium text-sm">{{ session('error') }}</p>
                <button onclick="document.getElementById('flash-error').remove()" class="ml-auto text-red-400 hover:text-red-600">✕</button>
            </div>
        </div>
        <script>setTimeout(() => { const el = document.getElementById('flash-error'); if(el) el.remove(); }, 5000);</script>
    @endif

    <!-- ── MAIN CONTENT ── -->
    <main>
        @yield('content')
    </main>

    <!-- ── FOOTER ── -->
    <footer class="bg-gray-900 text-gray-300 mt-20">
        <div class="max-w-7xl mx-auto px-6 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-9 h-9 blood-gradient rounded-lg flex items-center justify-center">
                            <span class="text-white text-lg">🩸</span>
                        </div>
                        <span class="font-display text-xl text-white font-bold">BloodLife</span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed max-w-xs">
                        Connecting generous donors with hospital blood donation campaigns. Every drop counts, every donor is a hero.
                    </p>
                    <div class="flex gap-3 mt-4">
                        <div class="bg-gray-800 rounded-lg px-3 py-2 text-center">
                            <p class="text-blood-400 font-bold text-lg">500+</p>
                            <p class="text-xs text-gray-500">Donors</p>
                        </div>
                        <div class="bg-gray-800 rounded-lg px-3 py-2 text-center">
                            <p class="text-blood-400 font-bold text-lg">50+</p>
                            <p class="text-xs text-gray-500">Campaigns</p>
                        </div>
                        <div class="bg-gray-800 rounded-lg px-3 py-2 text-center">
                            <p class="text-blood-400 font-bold text-lg">1200+</p>
                            <p class="text-xs text-gray-500">Lives Saved</p>
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Quick Links</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/campaigns" class="hover:text-blood-400 transition">Campaigns</a></li>
                        <li><a href="/register" class="hover:text-blood-400 transition">Become a Donor</a></li>
                        <li><a href="/login" class="hover:text-blood-400 transition">Donor Login</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Contact</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li>📍 Yaoundé, Cameroon</li>
                        <li>📞 +237 600 000 000</li>
                        <li>✉️ info@bloodlife.cm</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-10 pt-6 text-center text-xs text-gray-500">
                © {{ date('Y') }} BloodLife Hospital. All rights reserved. Built with ❤️ to save lives.
            </div>
        </div>
    </footer>

    <!-- Alpine.js for dropdown -->
    <script src="//unpkg.com/alpinejs" defer></script>
    @stack('scripts')
</body>
</html>
