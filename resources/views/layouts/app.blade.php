<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard Member – Roomify' }}</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts: Inter & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome 6.5 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50:  '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                        }
                    }
                }
            }
        }
        function getAutoTimeTheme() {
            const hour = new Date().getHours();
            return (hour >= 6 && hour < 18) ? 'light' : 'dark';
        }
        function initTheme() {
            if (localStorage.getItem('theme_user_override') === 'true' && !localStorage.getItem('theme_mode')) {
                localStorage.removeItem('theme_user_override');
                localStorage.setItem('theme_mode', 'auto');
            }
            const mode = localStorage.getItem('theme_mode') || 'auto';
            const effectiveTheme = (mode === 'auto') ? getAutoTimeTheme() : mode;
            if (effectiveTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
        function toggleTheme() {
            if (typeof window.cycleThemeMode === 'function') {
                window.cycleThemeMode();
            } else {
                const isDark = document.documentElement.classList.contains('dark');
                const nextMode = isDark ? 'light' : 'dark';
                localStorage.setItem('theme_mode', nextMode);
                if (nextMode === 'dark') document.documentElement.classList.add('dark');
                else document.documentElement.classList.remove('dark');
            }
        }
        initTheme();
    </script>
    <style>
        * { font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; }
        .glass-nav {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }
        .dark .glass-nav {
            background: rgba(15, 23, 42, 0.92);
            border-bottom: 1px solid rgba(51, 65, 85, 0.8);
        }
        .btn-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #ec4899 100%);
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.25);
            transition: all 0.2s ease;
        }
        .btn-gradient:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.35);
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 min-h-screen flex flex-col antialiased transition-colors duration-200">

    <!-- STICKY TOP BAR: LIVE CLOCK & NAVBAR -->
    <div class="sticky top-0 z-50">
        <x-live-clock-bar />
        <header class="glass-nav">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-18 py-3">
                
                <!-- Brand Logo -->
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 group no-underline">
                    <div class="w-9 h-9 relative transform transition-transform group-hover:scale-105">
                        <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full drop-shadow">
                            <defs>
                                <linearGradient id="app-nb-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#4F46E5"/>
                                    <stop offset="50%" stop-color="#7C3AED"/>
                                    <stop offset="100%" stop-color="#EC4899"/>
                                </linearGradient>
                                <linearGradient id="app-nb-gold" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#FCD34D"/>
                                    <stop offset="100%" stop-color="#F59E0B"/>
                                </linearGradient>
                            </defs>
                            <rect width="48" height="48" rx="14" fill="url(#app-nb-grad)"/>
                            <rect x="0.75" y="0.75" width="46.5" height="46.5" rx="13.25" stroke="rgba(255,255,255,0.3)" stroke-width="1.5"/>
                            <path d="M12 36V22C12 15.3726 17.3726 10 24 10C30.6274 10 36 15.3726 36 22V36" stroke="#FFFFFF" stroke-width="3" stroke-linecap="round"/>
                            <path d="M18 36V18H25.5C28 18 29.5 19.5 29.5 22C29.5 24.5 28 26 25.5 26H18M24 26L30 36" stroke="#FFFFFF" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M34 11L35.2 13.8L38 15L35.2 16.2L34 19L32.8 16.2L30 15L32.8 13.8L34 11Z" fill="url(#app-nb-gold)"/>
                            <circle cx="24" cy="31" r="1.5" fill="#FCD34D"/>
                        </svg>
                    </div>
                    <div class="leading-tight">
                        <span class="text-xl font-black bg-gradient-to-r from-indigo-700 via-purple-600 to-pink-500 bg-clip-text text-transparent tracking-tight">Roomify</span>
                    </div>
                </a>

                <!-- Nav Links -->
                <nav class="hidden md:flex items-center gap-6">
                    <a href="{{ url('/') }}" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition flex items-center gap-1.5">
                        <i class="fa-solid fa-house text-xs text-indigo-400"></i> Beranda
                    </a>
                    <a href="{{ url('/search') }}" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition flex items-center gap-1.5">
                        <i class="fa-solid fa-magnifying-glass text-xs text-indigo-400"></i> Cari Hotel
                    </a>
                    <a href="{{ url('/#destinasi') }}" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition flex items-center gap-1.5">
                        <i class="fa-solid fa-map-location-dot text-xs text-indigo-400"></i> Landmark
                    </a>
                    <a href="{{ route('dashboard') }}" class="text-sm font-bold text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-xl border border-indigo-100 flex items-center gap-1.5">
                        <i class="fa-solid fa-gauge-high text-xs text-indigo-600"></i> Dashboard
                    </a>
                </nav>

                <!-- User Profile & Actions -->
                <div class="flex items-center gap-3">
                    <button onclick="toggleTheme()" type="button" class="p-2 rounded-xl text-slate-500 hover:text-slate-700 dark:text-slate-300 dark:hover:text-white bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 transition" aria-label="Toggle theme">
                        <i class="fa-solid fa-moon hidden dark:inline text-amber-300"></i>
                        <i class="fa-solid fa-sun inline dark:hidden text-amber-500"></i>
                    </button>

                    @auth
                        <div class="relative" id="user-menu-container">
                            <button id="user-menu-button" onclick="document.getElementById('user-dropdown').classList.toggle('hidden')" class="flex items-center gap-2 p-1.5 pl-3 rounded-2xl bg-slate-100 hover:bg-slate-200/80 transition text-slate-700 text-sm font-semibold border border-slate-200">
                                <span class="max-w-[130px] truncate">{{ Auth::user()->name }}</span>
                                <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-indigo-600 to-purple-600 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 pr-1"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-60 bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-700 py-2 z-50">
                                <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700">
                                    <p class="text-[11px] text-slate-400">Masuk sebagai</p>
                                    <p class="text-xs font-bold text-slate-800 dark:text-white truncate">{{ Auth::user()->email }}</p>
                                    @if(Auth::user()->isAdmin())
                                        <span class="inline-block mt-1 text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full bg-purple-100 text-purple-700 dark:bg-purple-950/80 dark:text-purple-300">
                                            Admin
                                        </span>
                                    @endif
                                </div>
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold text-purple-600 dark:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-950/40 transition">
                                        <i class="fa-solid fa-shield-halved text-purple-500 w-4"></i> Admin Console
                                    </a>
                                @endif
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-indigo-50 dark:hover:bg-slate-700 transition">
                                    <i class="fa-solid fa-gauge text-indigo-500 w-4"></i> Dashboard Member
                                </a>
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-indigo-50 dark:hover:bg-slate-700 transition">
                                    <i class="fa-solid fa-user-pen text-indigo-500 w-4"></i> Edit Profil & Akun
                                </a>
                                <a href="{{ url('/search') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-indigo-50 dark:hover:bg-slate-700 transition">
                                    <i class="fa-solid fa-hotel text-indigo-500 w-4"></i> Cari Hotel di Medan
                                </a>
                                <div class="border-t border-slate-100 dark:border-slate-700 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition">
                                        <i class="fa-solid fa-right-from-bracket text-rose-500 w-4"></i> Keluar (Logout)
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 px-3 py-1.5 transition">Masuk</a>
                        <a href="{{ route('register') }}" class="btn-gradient text-white text-xs font-bold px-4 py-2 rounded-xl">Daftar</a>
                    @endauth
                </div>

            </div>
        </div>
    </header>
    </div>

    <!-- CONTENT DINAMIS -->
    <main class="flex-1">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- FOOTER KONSISTEN -->
    <footer class="bg-white dark:bg-slate-950 border-t border-slate-200 dark:border-slate-800 mt-auto py-8 px-4 sm:px-6 lg:px-8 text-center text-xs text-slate-500 dark:text-slate-400">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="font-extrabold text-slate-800 dark:text-slate-100">Roomify</span>
                <span>•</span>
                <span>Platform Pemesanan Hotel & Navigasi Google Maps</span>
            </div>
            <p>© {{ date('Y') }} Roomify. Hak cipta dilindungi undang-undang.</p>
        </div>
    </footer>

    <!-- Roomify AI Chatbot Concierge Component -->
    <x-ai-chat-widget />

    <script>
        document.addEventListener('click', function(e) {
            const container = document.getElementById('user-menu-container');
            const dropdown = document.getElementById('user-dropdown');
            if (container && dropdown && !container.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
