<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran – Roomify</title>
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
        .payment-option { transition: all 0.2s ease; }
        .payment-option:has(input:checked) { border-color: #4F46E5; background: #EEF2FF; }
        .dark .payment-option:has(input:checked) { border-color: #6366F1; background: rgba(79, 70, 229, 0.18); }
        .payment-detail { display: none; }
        .payment-option:has(input:checked) .payment-detail { display: block; }
    </style>
</head>
<body class="min-h-screen bg-slate-100 dark:bg-slate-900 text-slate-800 dark:text-slate-100 transition-colors duration-200 flex flex-col justify-between">

<div>
    {{-- LIVE SYSTEM CLOCK BAR & NAVBAR --}}
    <div class="sticky top-0 z-50">
        <x-live-clock-bar />
        <x-navbar active="search" />
    </div>

    {{-- PROGRESS STEPS --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center justify-center gap-1 sm:gap-2 mb-8">
            <div class="flex flex-col items-center">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold shadow-md">
                    <i class="fa fa-check text-xs sm:text-sm"></i>
                </div>
                <span class="text-[11px] sm:text-xs font-bold text-emerald-600 dark:text-emerald-400 mt-1">1. Isi Data</span>
            </div>
            <div class="h-1 w-12 sm:w-20 bg-gradient-to-r from-emerald-400 to-indigo-500 rounded"></div>
            <div class="flex flex-col items-center">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full gradient-hero text-white flex items-center justify-center font-extrabold shadow-lg ring-4 ring-indigo-500/20">
                    2
                </div>
                <span class="text-[11px] sm:text-xs font-bold text-indigo-600 dark:text-indigo-400 mt-1">2. Pembayaran</span>
            </div>
            <div class="h-1 w-12 sm:w-20 bg-slate-200 dark:bg-slate-700 rounded"></div>
            <div class="flex flex-col items-center">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-slate-200 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center font-bold">
                    3
                </div>
                <span class="text-[11px] sm:text-xs text-slate-400 dark:text-slate-500 mt-1">3. Konfirmasi</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- LEFT: Payment Methods --}}
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700 transition-colors">
                    <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white mb-1">Pilih Metode Pembayaran</h1>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mb-6">Pilih salah satu metode pembayaran terverifikasi otomatis di bawah ini:</p>

                    <form action="{{ route('payment.process') }}" method="POST" id="paymentForm">
                        @csrf

                        <div class="space-y-4">

                            {{-- Transfer Bank BCA --}}
                            <label class="payment-option block border-2 border-slate-200 dark:border-slate-700 rounded-2xl p-4 sm:p-5 cursor-pointer bg-white dark:bg-slate-900/50 hover:border-indigo-400 dark:hover:border-indigo-500 transition">
                                <input type="radio" name="payment_method" value="bca" class="sr-only" checked>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-14 sm:w-16 h-10 bg-blue-700 rounded-xl flex items-center justify-center shadow-xs shrink-0">
                                            <span class="text-white font-black text-sm">BCA</span>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 dark:text-white text-sm sm:text-base">Transfer Bank BCA</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">Verifikasi instan via Rekening BCA Roomify</p>
                                        </div>
                                    </div>
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-300 dark:border-slate-600 flex items-center justify-center radio-circle">
                                        <div class="w-2.5 h-2.5 rounded-full bg-indigo-600 radio-dot"></div>
                                    </div>
                                </div>
                                <div class="payment-detail mt-4 pt-4 border-t border-slate-200 dark:border-slate-700/80">
                                    <div class="bg-blue-50/80 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-900/60 rounded-2xl p-4 space-y-2.5">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-bold uppercase tracking-wider text-blue-800 dark:text-blue-300">Rekening Resmi BCA</span>
                                            <span class="text-[10px] bg-blue-200/60 dark:bg-blue-900/80 text-blue-800 dark:text-blue-200 px-2 py-0.5 rounded font-mono font-bold">Otomatis 24/7</span>
                                        </div>
                                        <div class="flex items-center justify-between flex-wrap gap-2 pt-1">
                                            <div>
                                                <span class="text-xs text-slate-500 dark:text-slate-400 block">No. Rekening</span>
                                                <span class="font-mono text-lg font-black text-slate-900 dark:text-white tracking-wider" id="bca-acc">1234-5678-90</span>
                                            </div>
                                            <button type="button" onclick="copyText('1234567890', this)" class="px-3 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold flex items-center gap-1.5 shadow-xs transition">
                                                <i class="fa-solid fa-copy"></i>
                                                <span>Salin No. Rek</span>
                                            </button>
                                        </div>
                                        <div class="flex items-center justify-between text-xs text-slate-600 dark:text-slate-300 pt-1 border-t border-blue-200/60 dark:border-blue-900/60">
                                            <span>Atas Nama</span>
                                            <span class="font-bold text-slate-900 dark:text-white">PT Roomify Indonesia</span>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            {{-- Transfer Bank Mandiri --}}
                            <label class="payment-option block border-2 border-slate-200 dark:border-slate-700 rounded-2xl p-4 sm:p-5 cursor-pointer bg-white dark:bg-slate-900/50 hover:border-indigo-400 dark:hover:border-indigo-500 transition">
                                <input type="radio" name="payment_method" value="mandiri" class="sr-only">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-14 sm:w-16 h-10 bg-amber-500 rounded-xl flex items-center justify-center shadow-xs shrink-0">
                                            <span class="text-blue-950 font-black text-xs">MANDIRI</span>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 dark:text-white text-sm sm:text-base">Transfer Bank Mandiri</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">Transfer Livin' Mandiri atau ATM</p>
                                        </div>
                                    </div>
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-300 dark:border-slate-600 flex items-center justify-center radio-circle">
                                        <div class="w-2.5 h-2.5 rounded-full bg-indigo-600 hidden radio-dot"></div>
                                    </div>
                                </div>
                                <div class="payment-detail mt-4 pt-4 border-t border-slate-200 dark:border-slate-700/80">
                                    <div class="bg-amber-50/80 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900/60 rounded-2xl p-4 space-y-2.5">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-bold uppercase tracking-wider text-amber-800 dark:text-amber-300">Rekening Resmi Mandiri</span>
                                            <span class="text-[10px] bg-amber-200/60 dark:bg-amber-900/80 text-amber-900 dark:text-amber-200 px-2 py-0.5 rounded font-mono font-bold">Livin' Online</span>
                                        </div>
                                        <div class="flex items-center justify-between flex-wrap gap-2 pt-1">
                                            <div>
                                                <span class="text-xs text-slate-500 dark:text-slate-400 block">No. Rekening</span>
                                                <span class="font-mono text-lg font-black text-slate-900 dark:text-white tracking-wider" id="mandiri-acc">0987-6543-21</span>
                                            </div>
                                            <button type="button" onclick="copyText('0987654321', this)" class="px-3 py-1.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold flex items-center gap-1.5 shadow-xs transition">
                                                <i class="fa-solid fa-copy"></i>
                                                <span>Salin No. Rek</span>
                                            </button>
                                        </div>
                                        <div class="flex items-center justify-between text-xs text-slate-600 dark:text-slate-300 pt-1 border-t border-amber-200/60 dark:border-amber-900/60">
                                            <span>Atas Nama</span>
                                            <span class="font-bold text-slate-900 dark:text-white">PT Roomify Indonesia</span>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            {{-- Transfer Bank BNI --}}
                            <label class="payment-option block border-2 border-slate-200 dark:border-slate-700 rounded-2xl p-4 sm:p-5 cursor-pointer bg-white dark:bg-slate-900/50 hover:border-indigo-400 dark:hover:border-indigo-500 transition">
                                <input type="radio" name="payment_method" value="bni" class="sr-only">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-14 sm:w-16 h-10 bg-orange-600 rounded-xl flex items-center justify-center shadow-xs shrink-0">
                                            <span class="text-white font-black text-sm">BNI</span>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 dark:text-white text-sm sm:text-base">Transfer Bank BNI</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">Transfer BNI Mobile Banking / ATM</p>
                                        </div>
                                    </div>
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-300 dark:border-slate-600 flex items-center justify-center radio-circle">
                                        <div class="w-2.5 h-2.5 rounded-full bg-indigo-600 hidden radio-dot"></div>
                                    </div>
                                </div>
                                <div class="payment-detail mt-4 pt-4 border-t border-slate-200 dark:border-slate-700/80">
                                    <div class="bg-orange-50/80 dark:bg-orange-950/40 border border-orange-200 dark:border-orange-900/60 rounded-2xl p-4 space-y-2.5">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-bold uppercase tracking-wider text-orange-800 dark:text-orange-300">Rekening Resmi BNI</span>
                                            <span class="text-[10px] bg-orange-200/60 dark:bg-orange-900/80 text-orange-900 dark:text-orange-200 px-2 py-0.5 rounded font-mono font-bold">24 Jam</span>
                                        </div>
                                        <div class="flex items-center justify-between flex-wrap gap-2 pt-1">
                                            <div>
                                                <span class="text-xs text-slate-500 dark:text-slate-400 block">No. Rekening</span>
                                                <span class="font-mono text-lg font-black text-slate-900 dark:text-white tracking-wider" id="bni-acc">1122-3344-55</span>
                                            </div>
                                            <button type="button" onclick="copyText('1122334455', this)" class="px-3 py-1.5 rounded-xl bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold flex items-center gap-1.5 shadow-xs transition">
                                                <i class="fa-solid fa-copy"></i>
                                                <span>Salin No. Rek</span>
                                            </button>
                                        </div>
                                        <div class="flex items-center justify-between text-xs text-slate-600 dark:text-slate-300 pt-1 border-t border-orange-200/60 dark:border-orange-900/60">
                                            <span>Atas Nama</span>
                                            <span class="font-bold text-slate-900 dark:text-white">PT Roomify Indonesia</span>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            {{-- Virtual Account Multi-Bank --}}
                            <label class="payment-option block border-2 border-slate-200 dark:border-slate-700 rounded-2xl p-4 sm:p-5 cursor-pointer bg-white dark:bg-slate-900/50 hover:border-indigo-400 dark:hover:border-indigo-500 transition">
                                <input type="radio" name="payment_method" value="va" class="sr-only">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-14 sm:w-16 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-xs shrink-0">
                                            <span class="text-white font-black text-xs">VA</span>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 dark:text-white text-sm sm:text-base">Virtual Account (Semua Bank)</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">Verifikasi instan tanpa perlu unggah bukti transfer</p>
                                        </div>
                                    </div>
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-300 dark:border-slate-600 flex items-center justify-center radio-circle">
                                        <div class="w-2.5 h-2.5 rounded-full bg-indigo-600 hidden radio-dot"></div>
                                    </div>
                                </div>
                                <div class="payment-detail mt-4 pt-4 border-t border-slate-200 dark:border-slate-700/80">
                                    <div class="bg-indigo-50/80 dark:bg-indigo-950/40 border border-indigo-200 dark:border-indigo-900/60 rounded-2xl p-4 space-y-2">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-bold text-indigo-800 dark:text-indigo-300 uppercase tracking-wider">Nomor Virtual Account Roomify</span>
                                            <span class="text-[10px] bg-indigo-200/60 dark:bg-indigo-900/80 text-indigo-900 dark:text-indigo-200 px-2 py-0.5 rounded font-mono font-bold">Auto Check</span>
                                        </div>
                                        <div class="flex items-center justify-between flex-wrap gap-2 pt-1">
                                            <div>
                                                <span class="text-xs text-slate-500 dark:text-slate-400 block">Kode Bayar VA</span>
                                                <span class="font-mono text-lg font-black text-slate-900 dark:text-white tracking-wider">8808 1271 9081 2233</span>
                                            </div>
                                            <button type="button" onclick="copyText('8808127190812233', this)" class="px-3 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold flex items-center gap-1.5 shadow-xs transition">
                                                <i class="fa-solid fa-copy"></i>
                                                <span>Salin VA</span>
                                            </button>
                                        </div>
                                        <p class="text-xs text-indigo-700 dark:text-indigo-300 pt-1">
                                            <i class="fa-solid fa-bolt text-amber-500 mr-1"></i> Setelah transfer selesai, sistem akan mengkonfirmasi status tiket Anda dalam hitungan detik.
                                        </p>
                                    </div>
                                </div>
                            </label>

                            {{-- GoPay & QRIS --}}
                            <label class="payment-option block border-2 border-slate-200 dark:border-slate-700 rounded-2xl p-4 sm:p-5 cursor-pointer bg-white dark:bg-slate-900/50 hover:border-indigo-400 dark:hover:border-indigo-500 transition">
                                <input type="radio" name="payment_method" value="gopay" class="sr-only">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-14 sm:w-16 h-10 bg-emerald-500 rounded-xl flex items-center justify-center shadow-xs shrink-0">
                                            <span class="text-white font-black text-xs">GoPay</span>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <p class="font-bold text-slate-900 dark:text-white text-sm sm:text-base">GoPay & QRIS</p>
                                                <span class="bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 text-[10px] px-2 py-0.5 rounded-full font-bold">Instan</span>
                                            </div>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">Scan QRIS dari aplikasi Gojek atau e-wallet lainnya</p>
                                        </div>
                                    </div>
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-300 dark:border-slate-600 flex items-center justify-center radio-circle">
                                        <div class="w-2.5 h-2.5 rounded-full bg-indigo-600 hidden radio-dot"></div>
                                    </div>
                                </div>
                                <div class="payment-detail mt-4 pt-4 border-t border-slate-200 dark:border-slate-700/80">
                                    <div class="bg-emerald-50/80 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/60 rounded-2xl p-4">
                                        <p class="text-xs text-emerald-800 dark:text-emerald-300 font-semibold flex items-center gap-1.5">
                                            <i class="fa-solid fa-qrcode text-base"></i>
                                            Kode QRIS dinamis akan langsung ditampilkan di layar setelah tombol Konfirmasi ditekan.
                                        </p>
                                    </div>
                                </div>
                            </label>

                            {{-- OVO --}}
                            <label class="payment-option block border-2 border-slate-200 dark:border-slate-700 rounded-2xl p-4 sm:p-5 cursor-pointer bg-white dark:bg-slate-900/50 hover:border-indigo-400 dark:hover:border-indigo-500 transition">
                                <input type="radio" name="payment_method" value="ovo" class="sr-only">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-14 sm:w-16 h-10 bg-purple-700 rounded-xl flex items-center justify-center shadow-xs shrink-0">
                                            <span class="text-white font-black text-xs">OVO</span>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 dark:text-white text-sm sm:text-base">OVO SmartPay</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">Push notification langsung ke aplikasi OVO Anda</p>
                                        </div>
                                    </div>
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-300 dark:border-slate-600 flex items-center justify-center radio-circle">
                                        <div class="w-2.5 h-2.5 rounded-full bg-indigo-600 hidden radio-dot"></div>
                                    </div>
                                </div>
                                <div class="payment-detail mt-4 pt-4 border-t border-slate-200 dark:border-slate-700/80">
                                    <div class="bg-purple-50/80 dark:bg-purple-950/40 border border-purple-200 dark:border-purple-900/60 rounded-2xl p-4">
                                        <p class="text-xs text-purple-800 dark:text-purple-300 font-semibold flex items-center gap-1.5">
                                            <i class="fa-solid fa-mobile-screen-button text-base"></i>
                                            Notifikasi pop-up pembayaran akan dikirimkan ke nomor ponsel pemesan yang terdaftar.
                                        </p>
                                    </div>
                                </div>
                            </label>

                        </div>

                        @if (isset($errors) && $errors->any())
                        <div class="mt-4 bg-red-50 dark:bg-red-950/50 border border-red-200 dark:border-red-800 rounded-2xl p-4">
                            <p class="text-xs text-red-600 dark:text-red-400 font-bold">{{ $errors->first() }}</p>
                        </div>
                        @endif

                        <button type="submit"
                                class="w-full mt-8 gradient-hero text-white font-black py-4 px-6 rounded-2xl text-base shadow-xl hover:opacity-95 hover:shadow-2xl transition-all duration-200 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-shield-check text-lg"></i>
                            <span>Selesaikan Pembayaran Sekarang</span>
                            <i class="fa-solid fa-arrow-right ml-1"></i>
                        </button>
                        <p class="text-center text-xs text-slate-500 dark:text-slate-400 mt-3 flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-lock text-emerald-500"></i>
                            <span>256-bit SSL Encrypted · Transaksi Aman & Terverifikasi</span>
                        </p>
                    </form>
                </div>
            </div>

            {{-- RIGHT: Order Summary --}}
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl p-6 border border-slate-200 dark:border-slate-700 sticky top-24 transition-colors">
                    <h3 class="font-extrabold text-slate-900 dark:text-white text-lg mb-5 flex items-center gap-2">
                        <i class="fa-solid fa-clipboard-list text-indigo-500"></i>
                        <span>Ringkasan Pesanan</span>
                    </h3>

                    {{-- Hotel info --}}
                    @if(isset($booking['hotel_foto']))
                    <div class="rounded-2xl overflow-hidden mb-4 h-36 border border-slate-200 dark:border-slate-700 relative shadow-sm">
                        <img src="{{ $booking['hotel_foto'] }}" alt="{{ $booking['hotel_name'] ?? 'Hotel' }}"
                             class="w-full h-full object-cover"
                             onerror="this.src='/images/placeholder.svg'">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-3">
                            <span class="text-white text-xs font-bold flex items-center gap-1">
                                <i class="fa-solid fa-location-dot text-indigo-400"></i> Kota Medan
                            </span>
                        </div>
                    </div>
                    @endif

                    <h4 class="font-black text-slate-900 dark:text-white text-base leading-snug mb-1">{{ $booking['hotel_name'] ?? '–' }}</h4>
                    <p class="text-xs text-indigo-600 dark:text-indigo-400 mb-5 font-bold uppercase tracking-wider">{{ $booking['room_type'] ?? '–' }}</p>

                    <div class="space-y-3 text-xs">
                        <div class="flex items-center justify-between text-slate-600 dark:text-slate-400">
                            <span class="flex items-center gap-2"><i class="fa-solid fa-calendar-check text-indigo-500 w-4"></i> Check-in</span>
                            <span class="font-bold text-slate-900 dark:text-white">
                                {{ $booking['checkin'] ?? '–' }}
                                <span class="text-[10px] bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 px-1.5 py-0.5 rounded font-mono ml-1">14:00 WIB</span>
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-slate-600 dark:text-slate-400">
                            <span class="flex items-center gap-2"><i class="fa-solid fa-calendar-xmark text-purple-500 w-4"></i> Check-out</span>
                            <span class="font-bold text-slate-900 dark:text-white">
                                {{ $booking['checkout'] ?? '–' }}
                                <span class="text-[10px] bg-purple-50 dark:bg-purple-950 text-purple-600 dark:text-purple-400 px-1.5 py-0.5 rounded font-mono ml-1">12:00 WIB</span>
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-slate-600 dark:text-slate-400">
                            <span class="flex items-center gap-2"><i class="fa-solid fa-moon text-indigo-500 w-4"></i> Durasi</span>
                            <span class="font-bold text-slate-900 dark:text-white">{{ $booking['nights'] ?? 1 }} Malam</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-600 dark:text-slate-400">
                            <span class="flex items-center gap-2"><i class="fa-solid fa-users text-indigo-500 w-4"></i> Tamu</span>
                            <span class="font-bold text-slate-900 dark:text-white">{{ $booking['guests'] ?? 1 }} Orang</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-600 dark:text-slate-400">
                            <span class="flex items-center gap-2"><i class="fa-solid fa-user text-indigo-500 w-4"></i> Pemesan</span>
                            <span class="font-bold text-slate-900 dark:text-white truncate max-w-[150px]">{{ $booking['name'] ?? '–' }}</span>
                        </div>
                        @if(!empty($booking['no_ktp']))
                        <div class="flex items-center justify-between text-slate-600 dark:text-slate-400">
                            <span class="flex items-center gap-2"><i class="fa-solid fa-id-card text-emerald-500 w-4"></i> NIK e-KTP</span>
                            <span class="font-mono text-xs font-bold text-slate-900 dark:text-white">{{ substr($booking['no_ktp'], 0, 6) }}******{{ substr($booking['no_ktp'], -4) }}</span>
                        </div>
                        @endif
                    </div>

                    {{-- Price Breakdown --}}
                    @php
                        $nights   = $booking['nights'] ?? 1;
                        $perMalam = $booking['room_price'] ?? 0;
                        $subtotal = $booking['subtotal'] ?? ($perMalam * $nights);
                        $tax      = (int) round($subtotal * 0.10);
                        $total    = $subtotal + $tax;
                    @endphp

                    <div class="border-t border-dashed border-slate-200 dark:border-slate-700 mt-5 pt-4 space-y-2 text-xs">
                        <div class="flex justify-between text-slate-600 dark:text-slate-400">
                            <span>Rp {{ number_format($perMalam, 0, ',', '.') }} × {{ $nights }} malam</span>
                            <span class="font-bold text-slate-900 dark:text-white">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600 dark:text-slate-400">
                            <span>Pajak & Layanan (10%)</span>
                            <span class="font-bold text-slate-900 dark:text-white">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t border-slate-200 dark:border-slate-700 pt-3 flex justify-between items-center">
                            <span class="font-black text-slate-900 dark:text-white text-sm">Total Bayar</span>
                            <span class="font-black text-indigo-600 dark:text-indigo-400 text-xl">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mt-5 bg-emerald-50/80 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 rounded-2xl p-3.5 flex items-start gap-2.5">
                        <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-sm mt-0.5"></i>
                        <span class="text-xs text-emerald-800 dark:text-emerald-300 font-semibold leading-relaxed">
                            Garansi harga termurah Roomify & pembatalan gratis hingga 24 jam sebelum waktu check-in.
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- TOAST NOTIFICATION FOR COPY --}}
<div id="copy-toast" class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-slate-900 text-white dark:bg-white dark:text-slate-900 px-4 py-2 rounded-xl text-xs font-bold shadow-2xl opacity-0 pointer-events-none transition-all duration-300 z-50 flex items-center gap-2">
    <i class="fa-solid fa-check text-emerald-400"></i>
    <span id="copy-toast-text">Nomor berhasil disalin!</span>
</div>

<script>
    // Radio visual update
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function () {
            document.querySelectorAll('.radio-circle').forEach(el => {
                el.classList.remove('border-indigo-500');
                el.classList.add('border-slate-300', 'dark:border-slate-600');
            });
            document.querySelectorAll('.radio-dot').forEach(el => el.classList.add('hidden'));

            const label = this.closest('label');
            const dot   = label.querySelector('.radio-dot');
            const circle = label.querySelector('.radio-circle');
            if (dot) dot.classList.remove('hidden');
            if (circle) {
                circle.classList.remove('border-slate-300', 'dark:border-slate-600');
                circle.classList.add('border-indigo-500');
            }
        });
    });

    // Copy to clipboard helper
    function copyText(val, btn) {
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

        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-check text-emerald-300"></i> <span>Tersalin!</span>';
        btn.classList.add('bg-emerald-600');

        const toast = document.getElementById('copy-toast');
        const toastText = document.getElementById('copy-toast-text');
        toastText.textContent = 'Nomor ' + val + ' berhasil disalin!';
        toast.classList.remove('opacity-0', 'pointer-events-none');
        toast.classList.add('opacity-100');

        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('bg-emerald-600');
            toast.classList.add('opacity-0', 'pointer-events-none');
            toast.classList.remove('opacity-100');
        }, 2000);
    }
</script>

    <!-- Branded Footer -->
    <footer class="mt-20 border-t border-slate-200 dark:border-slate-800 py-10" style="background: linear-gradient(135deg, #1e3a8a 0%, #1e1b4b 100%);">
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
</body>
</html>