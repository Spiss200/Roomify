<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Roomify Medan') }} – Masuk & Daftar Akun</title>

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
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        * { font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; }
        .auth-bg {
            background: radial-gradient(circle at 10% 20%, rgba(99, 102, 241, 0.15) 0%, transparent 40%),
                        radial-gradient(circle at 90% 80%, rgba(236, 72, 153, 0.12) 0%, transparent 40%),
                        linear-gradient(135deg, #f8fafc 0%, #f1f5f9 50%, #e2e8f0 100%);
            min-height: 100vh;
        }
        .btn-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #ec4899 100%);
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
            transition: all 0.2s ease;
        }
        .btn-gradient:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
        }
    </style>
</head>
<body class="auth-bg text-slate-800 antialiased flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8">

    <!-- Brand Logo Top -->
    <div class="mb-6">
        <a href="{{ url('/') }}" class="flex items-center gap-3 group no-underline">
            <div class="w-11 h-11 relative transform transition-transform group-hover:scale-105">
                <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full drop-shadow">
                    <defs>
                        <linearGradient id="guest-nb-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#4F46E5"/>
                            <stop offset="50%" stop-color="#7C3AED"/>
                            <stop offset="100%" stop-color="#EC4899"/>
                        </linearGradient>
                        <linearGradient id="guest-nb-gold" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#FCD34D"/>
                            <stop offset="100%" stop-color="#F59E0B"/>
                        </linearGradient>
                    </defs>
                    <rect width="48" height="48" rx="14" fill="url(#guest-nb-grad)"/>
                    <rect x="0.75" y="0.75" width="46.5" height="46.5" rx="13.25" stroke="rgba(255,255,255,0.3)" stroke-width="1.5"/>
                    <path d="M12 36V22C12 15.3726 17.3726 10 24 10C30.6274 10 36 15.3726 36 22V36" stroke="#FFFFFF" stroke-width="3" stroke-linecap="round"/>
                    <path d="M18 36V18H25.5C28 18 29.5 19.5 29.5 22C29.5 24.5 28 26 25.5 26H18M24 26L30 36" stroke="#FFFFFF" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M34 11L35.2 13.8L38 15L35.2 16.2L34 19L32.8 16.2L30 15L32.8 13.8L34 11Z" fill="url(#guest-nb-gold)"/>
                    <circle cx="24" cy="31" r="1.5" fill="#FCD34D"/>
                </svg>
            </div>
            <div class="leading-tight">
                <span class="text-2xl font-black bg-gradient-to-r from-indigo-700 via-purple-600 to-pink-500 bg-clip-text text-transparent tracking-tight">Roomify</span>
            </div>
        </a>
    </div>

    <!-- Auth Card -->
    <div class="w-full sm:max-w-md bg-white rounded-3xl p-8 shadow-2xl border border-slate-200/80">
        {{ $slot }}
    </div>

    <div class="mt-6 text-center text-xs text-slate-400">
        <a href="{{ url('/') }}" class="hover:text-indigo-600 transition flex items-center justify-center gap-1.5">
            <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali ke Beranda Roomify
        </a>
    </div>

</body>
</html>
