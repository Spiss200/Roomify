<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pemesanan Berhasil – Roomify</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] } } }
        }

        window.getAutoTimeTheme = function() {
            var now = new Date();
            var h = now.getHours();
            return (h >= 6 && h < 18) ? 'light' : 'dark';
        };

        (function() {
            try { localStorage.removeItem('theme_user_override'); } catch(e){}
            var mode = localStorage.getItem('theme_mode') || 'auto';
            var effectiveTheme = (mode === 'auto') ? window.getAutoTimeTheme() : mode;
            if (effectiveTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();

        window.applyRoomifyTheme = function(theme) {
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        };

        window.cycleThemeMode = function() {
            var currentMode = localStorage.getItem('theme_mode') || 'auto';
            var nextMode = 'auto';
            if (currentMode === 'auto') nextMode = 'light';
            else if (currentMode === 'light') nextMode = 'dark';
            else nextMode = 'auto';

            localStorage.setItem('theme_mode', nextMode);
            var effective = (nextMode === 'auto') ? window.getAutoTimeTheme() : nextMode;
            window.applyRoomifyTheme(effective);
            if (window.updateThemeBadge) window.updateThemeBadge();
        };

        window.toggleTheme = function() {
            window.cycleThemeMode();
        };
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
        .delay-1 { animation-delay: 0.2s; }
        .delay-2 { animation-delay: 0.4s; }
        .delay-3 { animation-delay: 0.6s; }
        .delay-4 { animation-delay: 0.8s; }

        @keyframes confetti-fall {
            0% { transform: translateY(-100%) rotate(0deg); opacity: 1; }
            100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
        }
        .confetti-piece { position: fixed; width: 10px; height: 10px; top: -10px; animation: confetti-fall 3s ease-in forwards; pointer-events: none; z-index: 60; }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-slate-100 dark:bg-slate-900 text-slate-800 dark:text-slate-100 transition-colors duration-200 justify-between">

<div>
    {{-- LIVE SYSTEM CLOCK BAR & NAVBAR --}}
    <div class="sticky top-0 z-50">
        <x-live-clock-bar />
        <x-navbar active="search" />
    </div>

    {{-- CONFETTI CONTAINER --}}
    <div id="confetti-container"></div>

    {{-- MAIN CONTENT --}}
    <main class="py-8 sm:py-12 px-4">
        <div class="max-w-2xl mx-auto w-full">

            {{-- Success Card --}}
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-700 bounce-in transition-colors">

                {{-- Top gradient banner --}}
                <div class="gradient-hero px-6 sm:px-8 py-10 text-center text-white relative">
                    {{-- Animated Checkmark SVG --}}
                    <div class="flex justify-center mb-4">
                        <svg class="w-20 h-20 sm:w-24 sm:h-24" viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="2"/>
                            <circle cx="26" cy="26" r="25" fill="rgba(255,255,255,0.2)"/>
                            <path class="checkmark" d="M14.1 27.2l7.1 7.2 16.7-16.8" fill="none" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <span class="inline-block px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-xs font-black uppercase tracking-wider mb-2">
                        Reservasi Terverifikasi Otomatis
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-black text-white mb-2">Pembayaran Berhasil!</h1>
                    <p class="text-white/90 text-sm max-w-md mx-auto leading-relaxed">
                        Terima kasih telah memilih Roomify. E-Voucher kamar Anda telah resmi diterbitkan.
                    </p>
                </div>

                {{-- Invoice Number --}}
                <div class="bg-indigo-50/80 dark:bg-indigo-950/60 border-b border-indigo-100 dark:border-indigo-900/60 px-6 sm:px-8 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 fade-up delay-1">
                    <div>
                        <p class="text-[11px] text-indigo-600 dark:text-indigo-400 font-extrabold uppercase tracking-wider">Nomor Booking & Invoice</p>
                        <div class="flex items-center gap-2 mt-0.5">
                            <p class="text-xl sm:text-2xl font-black text-indigo-800 dark:text-indigo-300 font-mono tracking-wider" id="invoice-text">{{ $invoice ?? 'INV-XXXXXXXX' }}</p>
                            <button type="button" onclick="copyInvoice('{{ $invoice ?? 'INV-XXXXXXXX' }}', this)" class="p-1.5 rounded-lg bg-indigo-100 hover:bg-indigo-200 dark:bg-indigo-900 dark:hover:bg-indigo-800 text-indigo-700 dark:text-indigo-300 text-xs transition" title="Salin Nomor Invoice">
                                <i class="fa-solid fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    <div class="self-start sm:self-center bg-emerald-100 dark:bg-emerald-950/80 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 px-3.5 py-1.5 rounded-full text-xs font-bold flex items-center gap-1.5">
                        <i class="fa-solid fa-check-circle"></i>
                        <span>Lunas & Terkonfirmasi</span>
                    </div>
                </div>

                {{-- Booking Details --}}
                <div class="px-6 sm:px-8 py-6 fade-up delay-2">
                    <h2 class="font-extrabold text-slate-900 dark:text-white mb-5 flex items-center gap-2 text-base sm:text-lg">
                        <i class="fa-solid fa-clipboard-list text-indigo-500"></i>
                        <span>Rincian Reservasi Kamar</span>
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-slate-50 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-700/80 rounded-2xl p-4">
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wide mb-1">Hotel Tujuan</p>
                            <p class="font-extrabold text-slate-900 dark:text-white text-sm sm:text-base">{{ $booking['hotel_name'] ?? '–' }}</p>
                            <p class="text-xs text-indigo-600 dark:text-indigo-400 font-semibold mt-0.5">Kota Medan</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-700/80 rounded-2xl p-4">
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wide mb-1">Tipe Kamar</p>
                            <p class="font-extrabold text-slate-900 dark:text-white text-sm sm:text-base">{{ $booking['room_type'] ?? '–' }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $booking['nights'] ?? 1 }} Malam · {{ $booking['guests'] ?? 1 }} Tamu</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-700/80 rounded-2xl p-4">
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wide mb-1">Jadwal Check-in</p>
                            <p class="font-extrabold text-slate-900 dark:text-white text-sm">
                                @php
                                    try { echo \Carbon\Carbon::parse($booking['checkin'] ?? '')->isoFormat('D MMMM YYYY'); } catch(\Exception $e) { echo $booking['checkin'] ?? '–'; }
                                @endphp
                            </p>
                            <span class="inline-block text-xs font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/80 px-2 py-0.5 rounded mt-1">Mulai pukul 14:00 WIB</span>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-700/80 rounded-2xl p-4">
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wide mb-1">Jadwal Check-out</p>
                            <p class="font-extrabold text-slate-900 dark:text-white text-sm">
                                @php
                                    try { echo \Carbon\Carbon::parse($booking['checkout'] ?? '')->isoFormat('D MMMM YYYY'); } catch(\Exception $e) { echo $booking['checkout'] ?? '–'; }
                                @endphp
                            </p>
                            <span class="inline-block text-xs font-bold text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-950/80 px-2 py-0.5 rounded mt-1">Maksimal pukul 12:00 WIB</span>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-700/80 rounded-2xl p-4">
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wide mb-1">Nama Pemesan</p>
                            <p class="font-extrabold text-slate-900 dark:text-white text-sm">{{ $booking['name'] ?? '–' }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $booking['email'] ?? '–' }}</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-700/80 rounded-2xl p-4">
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wide mb-1">Identitas e-KTP (NIK)</p>
                            <p class="font-mono text-xs font-black text-slate-900 dark:text-white tracking-wider">
                                {{ !empty($booking['no_ktp']) ? (substr($booking['no_ktp'], 0, 6) . '******' . substr($booking['no_ktp'], -4)) : '127101******8901' }}
                            </p>
                            <span class="inline-flex items-center gap-1 text-[10px] text-emerald-600 dark:text-emerald-400 font-bold mt-1">
                                <i class="fa-solid fa-shield-check"></i> Terverifikasi Resepsionis
                            </span>
                        </div>
                    </div>

                    {{-- Payment Summary --}}
                    @php
                        $nights  = $booking['nights'] ?? 1;
                        $price   = $booking['room_price'] ?? 0;
                        $sub     = $booking['subtotal'] ?? ($price * $nights);
                        $taxAmt  = $tax ?? (int) round($sub * 0.10);
                        $tot     = $total ?? ($sub + $taxAmt);
                        $methods = ['bca' => 'Transfer Bank BCA', 'mandiri' => 'Transfer Bank Mandiri', 'bni' => 'Transfer Bank BNI', 'va' => 'Virtual Account', 'gopay' => 'GoPay / QRIS', 'ovo' => 'OVO SmartPay'];
                        $methodLabel = $methods[$payment_method ?? ''] ?? ($payment_method ?? 'Transfer Bank');
                    @endphp

                    <div class="mt-6 bg-indigo-50/70 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/60 rounded-2xl p-5">
                        <h3 class="font-black text-indigo-900 dark:text-indigo-300 mb-3 flex items-center gap-2 text-sm">
                            <i class="fa-solid fa-receipt"></i>
                            <span>Rincian Pembayaran Lunas</span>
                        </h3>
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between text-slate-600 dark:text-slate-400">
                                <span>Metode Pembayaran</span>
                                <span class="font-bold text-slate-900 dark:text-white">{{ $methodLabel }}</span>
                            </div>
                            <div class="flex justify-between text-slate-600 dark:text-slate-400">
                                <span>Kamar ({{ $nights }} malam)</span>
                                <span class="font-bold text-slate-900 dark:text-white">Rp {{ number_format($sub, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-slate-600 dark:text-slate-400">
                                <span>Pajak & Layanan (10%)</span>
                                <span class="font-bold text-slate-900 dark:text-white">Rp {{ number_format($taxAmt, 0, ',', '.') }}</span>
                            </div>
                            <div class="border-t border-indigo-200 dark:border-indigo-800/80 pt-2.5 flex justify-between items-center">
                                <span class="font-extrabold text-indigo-950 dark:text-indigo-200 text-sm">Total Lunas</span>
                                <span class="font-black text-indigo-700 dark:text-indigo-400 text-xl">Rp {{ number_format($tot, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Info Notice Box --}}
                <div class="px-6 sm:px-8 pb-4 fade-up delay-3">
                    <div class="bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 rounded-2xl p-4 flex items-start gap-3">
                        <i class="fa-solid fa-envelope-circle-check text-amber-500 text-lg mt-0.5 shrink-0"></i>
                        <div class="text-xs text-amber-900 dark:text-amber-200 leading-relaxed">
                            <p class="font-extrabold mb-0.5">E-Voucher telah otomatis dikirimkan:</p>
                            <p class="text-amber-700 dark:text-amber-300">
                                E-voucher resmi dikirim ke <strong class="text-amber-900 dark:text-amber-100">{{ $booking['email'] ?? 'email Anda' }}</strong> dan tersimpan permanen di riwayat akun Roomify.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Action CTA Buttons --}}
                <div class="px-6 sm:px-8 pb-8 pt-2 flex flex-col sm:flex-row gap-3 fade-up delay-4">
                    <button type="button" onclick="window.print()" class="flex-1 flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-800 dark:text-slate-100 font-bold py-3.5 px-4 rounded-2xl text-xs transition shadow-xs">
                        <i class="fa-solid fa-print"></i>
                        <span>Cetak E-Tiket</span>
                    </button>
                    @auth
                    <a href="{{ route('dashboard') }}"
                       class="flex-1 flex items-center justify-center gap-2 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/80 dark:hover:bg-indigo-900/80 text-indigo-700 dark:text-indigo-300 font-bold py-3.5 px-4 rounded-2xl border border-indigo-200 dark:border-indigo-800 text-xs transition shadow-xs">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span>Lihat di Dashboard</span>
                    </a>
                    @endauth
                    <a href="{{ url('/search') }}"
                       class="flex-1 flex items-center justify-center gap-2 gradient-hero text-white font-black py-3.5 px-4 rounded-2xl shadow-lg hover:opacity-95 text-xs transition">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span>Cari Hotel Lain</span>
                    </a>
                </div>
            </div>

            <p class="text-center text-xs text-slate-400 mt-6">
                Butuh bantuan check-in? Hubungi Resepsionis atau Concierge AI Roomify 24 Jam.
            </p>
        </div>
    </main>
</div>

{{-- TOAST --}}
<div id="copy-toast" class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-slate-900 text-white dark:bg-white dark:text-slate-900 px-4 py-2 rounded-xl text-xs font-bold shadow-2xl opacity-0 pointer-events-none transition-all duration-300 z-50 flex items-center gap-2">
    <i class="fa-solid fa-check text-emerald-400"></i>
    <span id="copy-toast-text">Invoice disalin!</span>
</div>

<script>
    function copyInvoice(val, btn) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(val);
        } else {
            const input = document.createElement('input');
            input.value = val;
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
        }

        const toast = document.getElementById('copy-toast');
        const toastText = document.getElementById('copy-toast-text');
        toastText.textContent = 'Nomor Invoice ' + val + ' berhasil disalin!';
        toast.classList.remove('opacity-0', 'pointer-events-none');
        toast.classList.add('opacity-100');

        setTimeout(() => {
            toast.classList.add('opacity-0', 'pointer-events-none');
            toast.classList.remove('opacity-100');
        }, 2500);
    }

    // Confetti burst animation
    const colors = ['#4F46E5', '#7C3AED', '#EC4899', '#F59E0B', '#10B981', '#3B82F6'];
    const container = document.getElementById('confetti-container');
    if (container) {
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
    }
</script>

    <!-- Branded Footer -->
    <x-footer />
</body>
</html>