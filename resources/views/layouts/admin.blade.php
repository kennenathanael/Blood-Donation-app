<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — @yield('title', 'Dashboard') | BloodLife</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                colors: { blood: { 50:'#fff1f1',100:'#ffe0e0',200:'#ffc5c5',300:'#ff9d9d',400:'#ff6464',500:'#ff2d2d',600:'#e81010',700:'#c30a0a',800:'#a10d0d',900:'#850f0f' } },
                fontFamily: { display:['Playfair Display','serif'], body:['DM Sans','sans-serif'] }
            }}
        }
    </script>
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .blood-gradient { background: linear-gradient(135deg, #c30a0a 0%, #e81010 60%, #ff2d2d 100%); }
        .status-pending   { background:#fef9c3; color:#854d0e; border:1px solid #fde047; }
        .status-accepted  { background:#dcfce7; color:#166534; border:1px solid #86efac; }
        .status-rejected  { background:#fee2e2; color:#991b1b; border:1px solid #fca5a5; }
        .status-donated   { background:#dbeafe; color:#1e40af; border:1px solid #93c5fd; }
        .status-cancelled { background:#f3f4f6; color:#374151; border:1px solid #d1d5db; }
        .status-active    { background:#dcfce7; color:#166534; border:1px solid #86efac; }
        .status-upcoming  { background:#dbeafe; color:#1e40af; border:1px solid #93c5fd; }
        .status-completed { background:#f3f4f6; color:#374151; border:1px solid #d1d5db; }
        @keyframes fadeInUp { from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)} }
        .animate-fadeInUp { animation: fadeInUp 0.4s ease forwards; }
        .nav-item { border-left: 3px solid transparent; transition: all 0.15s; }
        .nav-item:hover, .nav-item.active { background:rgba(255,255,255,0.1); border-left-color: #ff6464; }
        .nav-item.active { background:rgba(255,255,255,0.15); }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100 min-h-screen flex">

    <!-- ── SIDEBAR ── -->
    <aside class="w-64 min-h-screen blood-gradient flex flex-col shadow-2xl fixed left-0 top-0 z-40">

        <!-- Logo -->
        <div class="p-6 border-b border-white/20">
            <a href="/admin/dashboard" class="flex items-center gap-2">
                <div class="w-9 h-9 bg-white/20 rounded-lg flex items-center justify-center">
                    <span class="text-white text-lg">🩸</span>
                </div>
                <div>
                    <p class="text-white font-bold text-sm">BloodLife</p>
                    <p class="text-white/60 text-xs">Admin Panel</p>
                </div>
            </a>
        </div>

        <!-- Admin Info -->
        <div class="px-5 py-4 border-b border-white/20">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center text-white font-bold text-sm">
                    {{ Auth::user()->initials ?? 'AD' }}
                </div>
                <div>
                    <p class="text-white text-sm font-medium">{{ Auth::user()->name }}</p>
                    <p class="text-white/60 text-xs">Administrator</p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 py-4 px-3 space-y-1">
            <p class="text-white/40 text-xs uppercase tracking-widest px-3 py-2">Main</p>

            <a href="/admin/dashboard"
               class="nav-item {{ request()->is('admin/dashboard') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/90 text-sm hover:text-white">
                <span class="text-lg">📊</span> Dashboard
            </a>

            <p class="text-white/40 text-xs uppercase tracking-widest px-3 pt-4 pb-2">Campaigns</p>

            <a href="/admin/campaigns"
               class="nav-item {{ request()->is('admin/campaigns*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/90 text-sm hover:text-white">
                <span class="text-lg">📅</span> All Campaigns
            </a>
            <a href="/admin/campaigns/create"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/90 text-sm hover:text-white">
                <span class="text-lg">➕</span> Create Campaign
            </a>

            <p class="text-white/40 text-xs uppercase tracking-widest px-3 pt-4 pb-2">Donors</p>

            <a href="/admin/donors"
               class="nav-item {{ request()->is('admin/donors*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/90 text-sm hover:text-white">
                <span class="text-lg">👥</span> All Donors
            </a>

            <p class="text-white/40 text-xs uppercase tracking-widest px-3 pt-4 pb-2">Other</p>

            <a href="/" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/90 text-sm hover:text-white">
                <span class="text-lg">🌐</span> Public Site
            </a>
        </nav>

        <!-- Logout -->
        <div class="p-4 border-t border-white/20">
            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2.5 rounded-lg text-white/80 hover:text-white hover:bg-white/10 text-sm transition">
                    <span>🚪</span> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- ── MAIN ── -->
    <div class="ml-64 flex-1 min-h-screen flex flex-col">

        <!-- Top Bar -->
        <header class="bg-white shadow-sm border-b border-gray-100 px-8 py-4 flex items-center justify-between sticky top-0 z-30">
            <div>
                <h1 class="text-lg font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                <p class="text-xs text-gray-400">@yield('page-subtitle', 'Manage your blood donation platform')</p>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</span>
                <a href="/admin/campaigns/create" class="blood-gradient text-white text-sm font-medium px-4 py-2 rounded-lg shadow hover:opacity-90 transition">
                    + New Campaign
                </a>
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
            <div id="flash-ok" class="mx-8 mt-4 animate-fadeInUp">
                <div class="bg-green-50 border border-green-200 text-green-800 px-5 py-3 rounded-xl flex items-center gap-3">
                    <span class="text-green-500">✓</span>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                    <button onclick="this.closest('#flash-ok').remove()" class="ml-auto text-green-400 hover:text-green-600">✕</button>
                </div>
            </div>
            <script>setTimeout(() => { const e = document.getElementById('flash-ok'); if(e) e.remove(); }, 5000);</script>
        @endif
        @if(session('error'))
            <div id="flash-err" class="mx-8 mt-4 animate-fadeInUp">
                <div class="bg-red-50 border border-red-200 text-red-800 px-5 py-3 rounded-xl flex items-center gap-3">
                    <span class="text-red-500">✕</span>
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                    <button onclick="this.closest('#flash-err').remove()" class="ml-auto text-red-400 hover:text-red-600">✕</button>
                </div>
            </div>
        @endif

        <!-- Validation Errors -->
        @if($errors->any())
            <div class="mx-8 mt-4 bg-red-50 border border-red-200 rounded-xl p-4">
                <p class="text-red-700 font-semibold text-sm mb-2">Please fix the following errors:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="text-red-600 text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Content -->
        <main class="flex-1 p-8">
            @yield('content')
        </main>
    </div>

    <script src="//unpkg.com/alpinejs" defer></script>
    @stack('scripts')
</body>
</html>
