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
        <x-navbar active="dashboard" />
    </div>

    <!-- CONTENT DINAMIS -->
    <main class="flex-1">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- FOOTER KONSISTEN -->
    <footer class="mt-auto border-t border-slate-200 dark:border-slate-800 py-10" style="background: linear-gradient(135deg, #1e3a8a 0%, #1e1b4b 100%);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <div class="flex items-center justify-center gap-2 mb-3">
                <span class="w-7 h-7 rounded-lg bg-blue-600 text-white flex items-center justify-center font-black text-sm">R</span>
                <span class="font-black text-lg tracking-tight">Roomify</span>
            </div>
            <p class="text-xs text-blue-200 max-w-md mx-auto leading-relaxed">
                Platform Pencarian & Navigasi Hotel Berbasis Web Semantik (Ontologi RDF, SPARQL Triplestore & Google Maps / OpenStreetMap) di Kota Medan.
            </p>
            <div class="mt-6 pt-4 border-t border-white/10 text-[11px] text-blue-300">
                &copy; {{ date('Y') }} Roomify Medan. Seluruh hak cipta dilindungi undang-undang.
            </div>
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
