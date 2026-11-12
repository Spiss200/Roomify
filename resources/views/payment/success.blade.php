<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil – Roomify</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] } } }
        }
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass { backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); }
        .gradient-hero { background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 50%, #EC4899 100%); }

        /* Animated checkmark */
        .checkmark-circle { stroke-dasharray: 166; stroke-dashoffset: 166; animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards; }
        .checkmark { stroke-dasharray: 48; stroke-dashoffset: 48; animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards; }
        @keyframes stroke { 100% { stroke-dashoffset: 0; } }

        .bounce-in { animation: bounceIn 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards; }
        @keyframes bounceIn {
            0% { opacity: 0; transform: scale(0.3) translateY(20px); }
            50% { opacity: 1; transform: scale(1.05) translateY(-5px); }
            70% { transform: scale(0.9) translateY(2px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }
        .fade-up { opacity: 0; animation: fadeUp 0.6s ease forwards; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .delay-1 { animation-delay: 0.3s; }
        .delay-2 { animation-delay: 0.5s; }
        .delay-3 { animation-delay: 0.7s; }
        .delay-4 { animation-delay: 0.9s; }

        /* Confetti animation */
        @keyframes confetti-fall {
            0% { transform: translateY(-100%) rotate(0deg); opacity: 1; }
            100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
        }
        .confetti-piece { position: fixed; width: 10px; height: 10px; top: -10px; animation: confetti-fall 3s ease-in forwards; }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-slate-100 dark:bg-slate-900 text-slate-800 dark:text-slate-100 transition-colors duration-200">

{{-- NAVBAR --}}
<nav class="glass bg-white/70 dark:bg-slate-900/70 border-b border-white/30 dark:border-slate-800 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 group no-underline">
                <div class="w-9 h-9 relative transform transition-transform group-hover:scale-105">
                    <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full drop-shadow">
                        <defs>
                            <linearGradient id="succ-nb-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#4F46E5"/>
                                <stop offset="50%" stop-color="#7C3AED"/>
                                <stop offset="100%" stop-color="#EC4899"/>
                            </linearGradient>
                            <linearGradient id="succ-nb-gold" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#FCD34D"/>
                                <stop offset="100%" stop-color="#F59E0B"/>
                            </linearGradient>
                        </defs>
                        <rect width="48" height="48" rx="14" fill="url(#succ-nb-grad)"/>
                        <rect x="0.75" y="0.75" width="46.5" height="46.5" rx="13.25" stroke="rgba(255,255,255,0.3)" stroke-width="1.5"/>
                        <path d="M12 36V22C12 15.3726 17.3726 10 24 10C30.6274 10 36 15.3726 36 22V36" stroke="#FFFFFF" stroke-width="3" stroke-linecap="round"/>
                        <path d="M18 36V18H25.5C28 18 29.5 19.5 29.5 22C29.5 24.5 28 26 25.5 26H18M24 26L30 36" stroke="#FFFFFF" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M34 11L35.2 13.8L38 15L35.2 16.2L34 19L32.8 16.2L30 15L32.8 13.8L34 11Z" fill="url(#succ-nb-gold)"/>
                        <circle cx="24" cy="31" r="1.5" fill="#FCD34D"/>
                    </svg>
                </div>
                <div class="leading-tight">
                    <span class="font-black text-xl bg-gradient-to-r from-indigo-700 via-purple-600 to-pink-500 bg-clip-text text-transparent tracking-tight">Roomify</span>
                </div>
            </a>
            <div class="flex items-center gap-3">
                <button onclick="toggleTheme()" type="button" class="p-2 rounded-xl text-slate-500 hover:text-slate-700 dark:text-slate-300 dark:hover:text-white bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 transition" aria-label="Toggle theme">
                    <i class="fa-solid fa-moon hidden dark:inline text-amber-300"></i>
                    <i class="fa-solid fa-sun inline dark:hidden text-amber-500"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

{{-- CONFETTI --}}
<div id="confetti-container"></div>

{{-- MAIN --}}
<main class="flex-1 flex items-center justify-center py-12 px-4">
    <div class="max-w-2xl w-full">

        {{-- Success Card --}}
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden bounce-in">

            {{-- Top gradient banner --}}
            <div class="gradient-hero px-8 py-10 text-center">
                {{-- Animated Checkmark SVG --}}
                <div class="flex justify-center mb-4">
                    <svg class="w-24 h-24" viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="2"/>
                        <circle cx="26" cy="26" r="25" fill="rgba(255,255,255,0.15)"/>
                        <path class="checkmark" d="M14.1 27.2l7.1 7.2 16.7-16.8" fill="none" stroke="white" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-extrabold text-white mb-2">Pemesanan Berhasil!</h1>
                <p class="text-white/80 text-base">Terima kasih telah memilih Roomify. Selamat menikmati liburan Anda!</p>
            </div>

            {{-- Invoice Number --}}
            <div class="bg-indigo-50 border-b border-indigo-100 px-8 py-4 flex items-center justify-between fade-up delay-1">
                <div>
                    <p class="text-xs text-indigo-500 font-semibold uppercase tracking-wide">Nomor Invoice</p>
                    <p class="text-2xl font-black text-indigo-700 font-mono tracking-wider">{{ $invoice ?? 'INV-XXXXXXXX' }}</p>
                </div>
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-bold flex items-center gap-2">
                    <i class="fa fa-check-circle"></i> Terkonfirmasi
                </div>
            </div>

            {{-- Booking Details --}}
            <div class="px-8 py-6 fade-up delay-2">
                <h2 class="font-bold text-gray-800 mb-5 flex items-center gap-2 text-lg">
                    <i class="fa fa-clipboard-list text-indigo-500"></i> Detail Pemesanan
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="bg-gray-50 rounded-2xl p-4">
                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-1">Hotel</p>
                        <p class="font-bold text-gray-800">{{ $booking['hotel_name'] ?? '–' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-2xl p-4">
                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-1">Tipe Kamar</p>
                        <p class="font-bold text-gray-800">{{ $booking['room_type'] ?? '–' }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-800 rounded-2xl p-4">
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wide mb-1">Check-in</p>
                        <p class="font-bold text-gray-800 dark:text-white">
                            @php
                                try { echo \Carbon\Carbon::parse($booking['checkin'] ?? '')->isoFormat('D MMMM YYYY'); } catch(\Exception $e) { echo $booking['checkin'] ?? '–'; }
                            @endphp
                            <span class="block text-xs text-indigo-600 dark:text-indigo-400 mt-0.5 font-medium">Mulai pukul 14:00 WIB</span>
                        </p>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-800 rounded-2xl p-4">
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wide mb-1">Check-out</p>
                        <p class="font-bold text-gray-800 dark:text-white">
                            @php
                                try { echo \Carbon\Carbon::parse($booking['checkout'] ?? '')->isoFormat('D MMMM YYYY'); } catch(\Exception $e) { echo $booking['checkout'] ?? '–'; }
                            @endphp
                            <span class="block text-xs text-purple-600 dark:text-purple-400 mt-0.5 font-medium">Maksimal pukul 12:00 WIB</span>
                        </p>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-800 rounded-2xl p-4">
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wide mb-1">Durasi</p>
                        <p class="font-bold text-gray-800 dark:text-white">{{ $booking['nights'] ?? '–' }} Malam</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-800 rounded-2xl p-4">
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wide mb-1">Jumlah Tamu</p>
                        <p class="font-bold text-gray-800 dark:text-white">{{ $booking['guests'] ?? '–' }} Orang</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-800 rounded-2xl p-4">
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wide mb-1">Nama Pemesan</p>
                        <p class="font-bold text-gray-800 dark:text-white">{{ $booking['name'] ?? '–' }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-800 rounded-2xl p-4">
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wide mb-1">Nomor KTP / NIK</p>
                        <p class="font-mono text-xs font-bold text-gray-800 dark:text-white tracking-wider">
                            {{ !empty($booking['no_ktp']) ? (substr($booking['no_ktp'], 0, 6) . '******' . substr($booking['no_ktp'], -4)) : '127101******8901' }}
                        </p>
                    </div>
                </div>

                {{-- Payment Summary --}}
                @php
                    $nights  = $booking['nights'] ?? 1;
                    $price   = $booking['room_price'] ?? 0;
                    $sub     = $booking['subtotal'] ?? ($price * $nights);
                    $taxAmt  = $tax ?? (int) round($sub * 0.10);
                    $tot     = $total ?? ($sub + $taxAmt);
                    $methods = ['bca' => 'Transfer Bank BCA', 'mandiri' => 'Transfer Bank Mandiri', 'bni' => 'Transfer Bank BNI', 'va' => 'Virtual Account', 'gopay' => 'GoPay', 'ovo' => 'OVO'];
                    $methodLabel = $methods[$payment_method ?? ''] ?? ($payment_method ?? '–');
                @endphp

                <div class="mt-6 bg-indigo-50 rounded-2xl p-5">
                    <h3 class="font-bold text-indigo-800 mb-4 flex items-center gap-2">
                        <i class="fa fa-receipt"></i> Rincian Pembayaran
                    </h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Metode Pembayaran</span>
                            <span class="font-semibold text-gray-800">{{ $methodLabel }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Rp {{ number_format($price, 0, ',', '.') }} × {{ $nights }} malam</span>
                            <span class="font-semibold">Rp {{ number_format($sub, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Pajak (10%)</span>
                            <span class="font-semibold">Rp {{ number_format($taxAmt, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t border-indigo-200 pt-3 flex justify-between items-center">
                            <span class="font-bold text-indigo-900 text-base">Total Dibayar</span>
                            <span class="font-extrabold text-indigo-700 text-xl">Rp {{ number_format($tot, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info box --}}
            <div class="px-8 pb-4 fade-up delay-3">
                <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-start gap-3">
                    <i class="fa fa-envelope text-amber-500 mt-0.5 flex-shrink-0"></i>
                    <div class="text-sm text-amber-800">
                        <p class="font-semibold mb-0.5">Konfirmasi dikirim ke email Anda</p>
                        <p class="text-amber-600">{{ $booking['email'] ?? '' }} — Cek folder spam jika tidak menerima dalam 5 menit.</p>
                    </div>
                </div>
            </div>

            {{-- CTA Buttons --}}
            <div class="px-8 pb-8 pt-2 flex flex-col sm:flex-row gap-3 fade-up delay-4">
                @auth
                <a href="{{ route('dashboard') }}"
                   class="flex-1 flex items-center justify-center gap-2 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/60 dark:hover:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 font-bold py-4 rounded-2xl border border-indigo-200 dark:border-indigo-800 transition-all duration-200">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    Riwayat Pemesanan Saya
                </a>
                @endauth
                <a href="{{ url('/') }}"
                   class="flex-1 flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-200 font-semibold py-4 rounded-2xl transition-all duration-200">
                    <i class="fa fa-home"></i>
                    Beranda
                </a>
                <a href="{{ url('/search') }}"
                   class="flex-1 flex items-center justify-center gap-2 gradient-hero text-white font-bold py-4 rounded-2xl shadow-lg hover:opacity-90 hover:shadow-xl transition-all duration-200">
                    <i class="fa fa-search"></i>
                    Cari Hotel Lain
                </a>
            </div>
        </div>

        {{-- Footer note --}}
        <p class="text-center text-xs text-gray-400 mt-6">
            Butuh bantuan? <a href="mailto:support@roomify.id" class="text-indigo-500 hover:underline">support@roomify.id</a>
        </p>
    </div>
</main>

<script>
    // Simple confetti burst
    const colors = ['#4F46E5', '#7C3AED', '#EC4899', '#F59E0B', '#10B981', '#3B82F6'];
    const container = document.getElementById('confetti-container');
    for (let i = 0; i < 60; i++) {
        const piece = document.createElement('div');
        piece.className = 'confetti-piece';
        piece.style.left = Math.random() * 100 + 'vw';
        piece.style.top = '-10px';
        piece.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
        piece.style.borderRadius = Math.random() > 0.5 ? '50%' : '2px';
        piece.style.width = (Math.random() * 8 + 6) + 'px';
        piece.style.height = (Math.random() * 8 + 6) + 'px';
        piece.style.animationDuration = (Math.random() * 2 + 2) + 's';
        piece.style.animationDelay = (Math.random() * 1.5) + 's';
        container.appendChild(piece);
        setTimeout(() => piece.remove(), 5000);
    }
</script>
</body>
</html>
