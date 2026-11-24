<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Roomify Medan') }} – Masuk & Daftar Akun</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

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
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50:  '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
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
        initTheme();
    </script>
    <style>
        * { font-family: 'Inter', sans-serif; }
        .btn-gradient {
            background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
            transition: all 0.2s ease;
        }
        .btn-gradient:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #4338ca 100%);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 antialiased flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8 min-h-screen transition-colors">

    <!-- Brand Logo Top -->
    <div class="mb-6">
        <a href="{{ url('/') }}" class="flex items-center gap-3 group no-underline">
            <x-application-logo class="w-11 h-11 drop-shadow transition transform group-hover:scale-105" />
            <div class="flex items-center gap-2">
                <span class="text-2xl font-black text-slate-900 dark:text-white tracking-tight group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">Roomify</span>
                <span class="text-[10px] font-bold uppercase tracking-wider text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-950/70 border border-blue-200/80 dark:border-blue-800/80 px-2 py-0.5 rounded-full">
                    Medan
                </span>
            </div>
        </a>
    </div>

    <!-- Auth Card -->
    <div class="w-full sm:max-w-md bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-xl dark:shadow-2xl border border-slate-200/80 dark:border-slate-800 transition-colors">
        {{ $slot }}
    </div>

    <div class="mt-6 text-center text-xs text-slate-400 dark:text-slate-500">
        <a href="{{ url('/') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition flex items-center justify-center gap-1.5">
            <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali ke Beranda Roomify
        </a>
    </div>

</body>
</html>