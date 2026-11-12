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
        .payment-option { transition: all 0.2s ease; }
        .payment-option:has(input:checked) { border-color: #4F46E5; background: #EEF2FF; }
        .dark .payment-option:has(input:checked) { border-color: #6366F1; background: rgba(79, 70, 229, 0.15); }
        .payment-detail { display: none; }
        .payment-option:has(input:checked) .payment-detail { display: block; }
    </style>
</head>
<body class="min-h-screen bg-slate-100 dark:bg-slate-900 text-slate-800 dark:text-slate-100 transition-colors duration-200">

{{-- NAVBAR --}}
<nav class="sticky top-0 z-50 glass bg-white/80 dark:bg-slate-900/80 border-b border-slate-200 dark:border-slate-800 shadow-sm transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 group no-underline">
                <div class="w-9 h-9 relative transform transition-transform group-hover:scale-105">
                    <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full drop-shadow">
                        <defs>
                            <linearGradient id="pay-nb-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#4F46E5"/>
                                <stop offset="50%" stop-color="#7C3AED"/>
                                <stop offset="100%" stop-color="#EC4899"/>
                            </linearGradient>
                            <linearGradient id="pay-nb-gold" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#FCD34D"/>
                                <stop offset="100%" stop-color="#F59E0B"/>
                            </linearGradient>
                        </defs>
                        <rect width="48" height="48" rx="14" fill="url(#pay-nb-grad)"/>
                        <rect x="0.75" y="0.75" width="46.5" height="46.5" rx="13.25" stroke="rgba(255,255,255,0.3)" stroke-width="1.5"/>
                        <path d="M12 36V22C12 15.3726 17.3726 10 24 10C30.6274 10 36 15.3726 36 22V36" stroke="#FFFFFF" stroke-width="3" stroke-linecap="round"/>
                        <path d="M18 36V18H25.5C28 18 29.5 19.5 29.5 22C29.5 24.5 28 26 25.5 26H18M24 26L30 36" stroke="#FFFFFF" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M34 11L35.2 13.8L38 15L35.2 16.2L34 19L32.8 16.2L30 15L32.8 13.8L34 11Z" fill="url(#pay-nb-gold)"/>
                        <circle cx="24" cy="31" r="1.5" fill="#FCD34D"/>
                    </svg>
                </div>
                <div class="leading-tight">
                    <span class="font-black text-xl bg-gradient-to-r from-indigo-700 via-purple-600 to-pink-500 bg-clip-text text-transparent tracking-tight">Roomify</span>
                </div>
            </a>
            <div class="flex items-center gap-4">
                <button onclick="toggleTheme()" type="button" class="p-2 rounded-xl text-slate-500 hover:text-slate-700 dark:text-slate-300 dark:hover:text-white bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 transition" aria-label="Toggle theme">
                    <i class="fa-solid fa-moon hidden dark:inline text-amber-300"></i>
                    <i class="fa-solid fa-sun inline dark:hidden text-amber-500"></i>
                </button>
                <span class="text-sm text-gray-500 dark:text-gray-400 hidden sm:inline"><i class="fa fa-lock text-green-500 mr-1"></i>Pembayaran Aman</span>
            </div>
        </div>
    </div>
</nav>

{{-- PROGRESS --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex items-center justify-center gap-0 mb-8">
        <div class="flex flex-col items-center">
            <div class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center font-bold shadow-lg">
                <i class="fa fa-check text-sm"></i>
            </div>
            <span class="text-xs font-semibold text-green-600 mt-1">Isi Data</span>
        </div>
        <div class="h-1 w-20 bg-gradient-to-r from-green-400 to-indigo-500 rounded mx-1"></div>
        <div class="flex flex-col items-center">
            <div class="w-10 h-10 rounded-full gradient-hero text-white flex items-center justify-center font-bold shadow-lg">2</div>
            <span class="text-xs font-semibold text-indigo-600 mt-1">Pembayaran</span>
        </div>
        <div class="h-1 w-20 bg-gray-200 rounded mx-1"></div>
        <div class="flex flex-col items-center">
            <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center font-bold">3</div>
            <span class="text-xs text-gray-400 mt-1">Konfirmasi</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- LEFT: Payment Methods --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-xl p-8">
                <h1 class="text-2xl font-extrabold text-gray-900 mb-1">Pilih Metode Pembayaran</h1>
                <p class="text-gray-500 text-sm mb-8">Semua transaksi dienkripsi dan aman</p>

                <form action="{{ route('payment.process') }}" method="POST" id="paymentForm">
                    @csrf

                    <div class="space-y-4">

                        {{-- Transfer Bank BCA --}}
                        <label class="payment-option block border-2 border-gray-200 rounded-2xl p-5 cursor-pointer hover:border-indigo-300">
                            <input type="radio" name="payment_method" value="bca" class="sr-only">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-10 bg-blue-700 rounded-lg flex items-center justify-center">
                                        <span class="text-white font-black text-sm">BCA</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">Transfer Bank BCA</p>
                                        <p class="text-xs text-gray-500">Transfer manual ke rekening BCA</p>
                                    </div>
                                </div>
                                <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center radio-circle">
                                    <div class="w-2.5 h-2.5 rounded-full bg-indigo-600 hidden radio-dot"></div>
                                </div>
                            </div>
                            <div class="payment-detail mt-4 pt-4 border-t border-indigo-100">
                                <div class="bg-blue-50 rounded-xl p-4 space-y-2">
                                    <p class="text-sm font-semibold text-blue-800">Informasi Rekening BCA</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">No. Rekening</span>
                                        <span class="font-bold text-gray-800 font-mono">1234-5678-90</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Atas Nama</span>
                                        <span class="font-semibold text-gray-800">PT Roomify Indonesia</span>
                                    </div>
                                    <p class="text-xs text-blue-600 mt-2"><i class="fa fa-info-circle mr-1"></i>Transfer sesuai nominal tepat untuk verifikasi otomatis</p>
                                </div>
                            </div>
                        </label>

                        {{-- Transfer Bank Mandiri --}}
                        <label class="payment-option block border-2 border-gray-200 rounded-2xl p-5 cursor-pointer hover:border-indigo-300">
                            <input type="radio" name="payment_method" value="mandiri" class="sr-only">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-10 bg-yellow-500 rounded-lg flex items-center justify-center">
                                        <span class="text-blue-900 font-black text-xs">MANDIRI</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">Transfer Bank Mandiri</p>
                                        <p class="text-xs text-gray-500">Transfer manual ke rekening Mandiri</p>
                                    </div>
                                </div>
                                <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center radio-circle">
                                    <div class="w-2.5 h-2.5 rounded-full bg-indigo-600 hidden radio-dot"></div>
                                </div>
                            </div>
                            <div class="payment-detail mt-4 pt-4 border-t border-indigo-100">
                                <div class="bg-yellow-50 rounded-xl p-4 space-y-2">
                                    <p class="text-sm font-semibold text-yellow-800">Informasi Rekening Mandiri</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">No. Rekening</span>
                                        <span class="font-bold text-gray-800 font-mono">0987-6543-21</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Atas Nama</span>
                                        <span class="font-semibold text-gray-800">PT Roomify Indonesia</span>
                                    </div>
                                </div>
                            </div>
                        </label>

                        {{-- Transfer Bank BNI --}}
                        <label class="payment-option block border-2 border-gray-200 rounded-2xl p-5 cursor-pointer hover:border-indigo-300">
                            <input type="radio" name="payment_method" value="bni" class="sr-only">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-10 bg-orange-600 rounded-lg flex items-center justify-center">
                                        <span class="text-white font-black text-sm">BNI</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">Transfer Bank BNI</p>
                                        <p class="text-xs text-gray-500">Transfer manual ke rekening BNI</p>
                                    </div>
                                </div>
                                <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center radio-circle">
                                    <div class="w-2.5 h-2.5 rounded-full bg-indigo-600 hidden radio-dot"></div>
                                </div>
                            </div>
                            <div class="payment-detail mt-4 pt-4 border-t border-indigo-100">
                                <div class="bg-orange-50 rounded-xl p-4 space-y-2">
                                    <p class="text-sm font-semibold text-orange-800">Informasi Rekening BNI</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">No. Rekening</span>
                                        <span class="font-bold text-gray-800 font-mono">1122-3344-55</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Atas Nama</span>
                                        <span class="font-semibold text-gray-800">PT Roomify Indonesia</span>
                                    </div>
                                </div>
                            </div>
                        </label>

                        {{-- Virtual Account --}}
                        <label class="payment-option block border-2 border-gray-200 rounded-2xl p-5 cursor-pointer hover:border-indigo-300">
                            <input type="radio" name="payment_method" value="va" class="sr-only">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                        <span class="text-white font-bold text-xs">VA</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">Virtual Account</p>
                                        <p class="text-xs text-gray-500">VA otomatis dari semua bank</p>
                                    </div>
                                </div>
                                <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center radio-circle">
                                    <div class="w-2.5 h-2.5 rounded-full bg-indigo-600 hidden radio-dot"></div>
                                </div>
                            </div>
                            <div class="payment-detail mt-4 pt-4 border-t border-indigo-100">
                                <div class="bg-indigo-50 rounded-xl p-4">
                                    <p class="text-sm text-indigo-700"><i class="fa fa-info-circle mr-1"></i>Nomor Virtual Account akan dikirimkan ke email Anda setelah konfirmasi.</p>
                                </div>
                            </div>
                        </label>

                        {{-- GoPay --}}
                        <label class="payment-option block border-2 border-gray-200 rounded-2xl p-5 cursor-pointer hover:border-indigo-300 opacity-80">
                            <input type="radio" name="payment_method" value="gopay" class="sr-only">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                        <span class="text-white font-black text-xs">GoPay</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">GoPay</p>
                                        <p class="text-xs text-gray-400">Segera hadir <span class="bg-yellow-100 text-yellow-700 text-xs px-1.5 py-0.5 rounded-full font-semibold">Beta</span></p>
                                    </div>
                                </div>
                                <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center radio-circle">
                                    <div class="w-2.5 h-2.5 rounded-full bg-indigo-600 hidden radio-dot"></div>
                                </div>
                            </div>
                            <div class="payment-detail mt-4 pt-4 border-t border-indigo-100">
                                <div class="bg-green-50 rounded-xl p-4">
                                    <p class="text-sm text-green-700"><i class="fa fa-mobile-screen mr-1"></i>Scan QR Code GoPay akan tampil setelah konfirmasi. (Demo)</p>
                                </div>
                            </div>
                        </label>

                        {{-- OVO --}}
                        <label class="payment-option block border-2 border-gray-200 rounded-2xl p-5 cursor-pointer hover:border-indigo-300 opacity-80">
                            <input type="radio" name="payment_method" value="ovo" class="sr-only">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-10 bg-purple-700 rounded-lg flex items-center justify-center">
                                        <span class="text-white font-black text-sm">OVO</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">OVO</p>
                                        <p class="text-xs text-gray-400">Segera hadir <span class="bg-yellow-100 text-yellow-700 text-xs px-1.5 py-0.5 rounded-full font-semibold">Beta</span></p>
                                    </div>
                                </div>
                                <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center radio-circle">
                                    <div class="w-2.5 h-2.5 rounded-full bg-indigo-600 hidden radio-dot"></div>
                                </div>
                            </div>
                            <div class="payment-detail mt-4 pt-4 border-t border-indigo-100">
                                <div class="bg-purple-50 rounded-xl p-4">
                                    <p class="text-sm text-purple-700"><i class="fa fa-mobile-screen mr-1"></i>Push notification OVO akan dikirim ke nomor Anda. (Demo)</p>
                                </div>
                            </div>
                        </label>

                    </div>

                    @if (isset($errors) && $errors->any())
                    <div class="mt-4 bg-red-50 border border-red-200 rounded-xl p-4">
                        <p class="text-sm text-red-600">{{ $errors->first() }}</p>
                    </div>
                    @endif

                    <button type="submit"
                            class="w-full mt-8 gradient-hero text-white font-bold py-4 rounded-2xl text-base shadow-xl hover:opacity-90 hover:shadow-2xl transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="fa fa-shield-check"></i>
                        Konfirmasi Pembayaran
                        <i class="fa fa-arrow-right ml-1"></i>
                    </button>
                    <p class="text-center text-xs text-gray-400 mt-3">
                        <i class="fa fa-lock mr-1"></i>256-bit SSL Encrypted · Aman & Terpercaya
                    </p>
                </form>
            </div>
        </div>

        {{-- RIGHT: Order Summary --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl shadow-xl p-6 sticky top-24">
                <h3 class="font-bold text-gray-800 text-lg mb-5 flex items-center gap-2">
                    <i class="fa fa-clipboard-list text-indigo-500"></i> Ringkasan Pesanan
                </h3>

                {{-- Hotel info --}}
                @if(isset($booking['hotel_foto']))
                <div class="rounded-xl overflow-hidden mb-4 h-32">
                    <img src="{{ $booking['hotel_foto'] }}" alt="{{ $booking['hotel_name'] ?? 'Hotel' }}"
                         class="w-full h-full object-cover"
                         onerror="this.src='/images/placeholder.svg'">
                </div>
                @endif

                <h4 class="font-bold text-gray-800 mb-1">{{ $booking['hotel_name'] ?? '–' }}</h4>
                <p class="text-sm text-indigo-600 mb-4 font-medium">{{ $booking['room_type'] ?? '–' }}</p>

                <div class="space-y-2.5 text-sm">
                    <div class="flex items-center justify-between text-gray-600 dark:text-gray-300">
                        <span class="flex items-center gap-2"><i class="fa fa-calendar-check text-indigo-400 w-4"></i> Check-in</span>
                        <span class="font-semibold text-gray-800 dark:text-white">{{ $booking['checkin'] ?? '–' }} <span class="text-xs bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 px-1.5 py-0.5 rounded">14:00 WIB</span></span>
                    </div>
                    <div class="flex items-center justify-between text-gray-600 dark:text-gray-300">
                        <span class="flex items-center gap-2"><i class="fa fa-calendar-xmark text-purple-400 w-4"></i> Check-out</span>
                        <span class="font-semibold text-gray-800 dark:text-white">{{ $booking['checkout'] ?? '–' }} <span class="text-xs bg-purple-50 dark:bg-purple-950 text-purple-600 dark:text-purple-400 px-1.5 py-0.5 rounded">12:00 WIB</span></span>
                    </div>
                    <div class="flex items-center justify-between text-gray-600 dark:text-gray-300">
                        <span class="flex items-center gap-2"><i class="fa fa-moon text-indigo-400 w-4"></i> Durasi</span>
                        <span class="font-semibold text-gray-800 dark:text-white">{{ $booking['nights'] ?? 1 }} malam</span>
                    </div>
                    <div class="flex items-center justify-between text-gray-600 dark:text-gray-300">
                        <span class="flex items-center gap-2"><i class="fa fa-users text-indigo-400 w-4"></i> Tamu</span>
                        <span class="font-semibold text-gray-800 dark:text-white">{{ $booking['guests'] ?? 1 }} orang</span>
                    </div>
                    <div class="flex items-center justify-between text-gray-600 dark:text-gray-300">
                        <span class="flex items-center gap-2"><i class="fa fa-user text-indigo-400 w-4"></i> Pemesan</span>
                        <span class="font-semibold text-gray-800 dark:text-white">{{ $booking['name'] ?? '–' }}</span>
                    </div>
                    @if(!empty($booking['no_ktp']))
                    <div class="flex items-center justify-between text-gray-600 dark:text-gray-300">
                        <span class="flex items-center gap-2"><i class="fa fa-id-card text-indigo-400 w-4"></i> NIK KTP</span>
                        <span class="font-mono text-xs font-semibold text-gray-800 dark:text-white">{{ substr($booking['no_ktp'], 0, 6) }}******{{ substr($booking['no_ktp'], -4) }}</span>
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

                <div class="border-t border-dashed border-gray-200 mt-5 pt-4 space-y-2">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Rp {{ number_format($perMalam, 0, ',', '.') }} × {{ $nights }} malam</span>
                        <span class="font-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Pajak (10%)</span>
                        <span class="font-semibold">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                    </div>
                    <div class="border-t border-gray-200 pt-3 flex justify-between items-center">
                        <span class="font-bold text-gray-800">Total</span>
                        <span class="font-extrabold text-indigo-600 text-xl">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="mt-4 bg-green-50 rounded-xl p-3 flex items-center gap-2">
                    <i class="fa fa-shield-check text-green-500"></i>
                    <span class="text-xs text-green-700 font-medium">Pembatalan gratis hingga 24 jam sebelum check-in</span>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    // Radio visual update
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function () {
            document.querySelectorAll('.radio-circle').forEach(el => {
                el.classList.remove('border-indigo-500');
                el.classList.add('border-gray-300');
            });
            document.querySelectorAll('.radio-dot').forEach(el => el.classList.add('hidden'));

            const label = this.closest('label');
            const dot   = label.querySelector('.radio-dot');
            const circle = label.querySelector('.radio-circle');
            if (dot) dot.classList.remove('hidden');
            if (circle) {
                circle.classList.remove('border-gray-300');
                circle.classList.add('border-indigo-500');
            }
        });
    });
</script>
</body>
</html>
