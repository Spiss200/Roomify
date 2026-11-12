<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Hotel – Roomify</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        primary: { DEFAULT: '#4F46E5', dark: '#3730A3', light: '#818CF8' },
                    }
                }
            }
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
        input:focus, select:focus, textarea:focus { outline: none; box-shadow: 0 0 0 3px rgba(79,70,229,0.2); }
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
                            <linearGradient id="book-nb-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#4F46E5"/>
                                <stop offset="50%" stop-color="#7C3AED"/>
                                <stop offset="100%" stop-color="#EC4899"/>
                            </linearGradient>
                            <linearGradient id="book-nb-gold" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#FCD34D"/>
                                <stop offset="100%" stop-color="#F59E0B"/>
                            </linearGradient>
                        </defs>
                        <rect width="48" height="48" rx="14" fill="url(#book-nb-grad)"/>
                        <rect x="0.75" y="0.75" width="46.5" height="46.5" rx="13.25" stroke="rgba(255,255,255,0.3)" stroke-width="1.5"/>
                        <path d="M12 36V22C12 15.3726 17.3726 10 24 10C30.6274 10 36 15.3726 36 22V36" stroke="#FFFFFF" stroke-width="3" stroke-linecap="round"/>
                        <path d="M18 36V18H25.5C28 18 29.5 19.5 29.5 22C29.5 24.5 28 26 25.5 26H18M24 26L30 36" stroke="#FFFFFF" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M34 11L35.2 13.8L38 15L35.2 16.2L34 19L32.8 16.2L30 15L32.8 13.8L34 11Z" fill="url(#book-nb-gold)"/>
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
                <a href="{{ url('/search') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:text-indigo-600 font-medium transition-colors">
                    <i class="fa fa-search mr-1"></i> Cari Hotel
                </a>
                <a href="{{ url('/') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:text-indigo-600 font-medium transition-colors">
                    <i class="fa fa-home mr-1"></i> Beranda
                </a>
            </div>
        </div>
    </div>
</nav>

{{-- BREADCRUMB --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
    <nav class="flex text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2">
            <li><a href="{{ url('/') }}" class="hover:text-indigo-600 transition-colors">Beranda</a></li>
            <li><i class="fa fa-chevron-right text-xs mx-1"></i></li>
            <li><a href="{{ url('/search') }}" class="hover:text-indigo-600 transition-colors">Cari Hotel</a></li>
            <li><i class="fa fa-chevron-right text-xs mx-1"></i></li>
            <li><a href="{{ route('hotel.detail', $id) }}" class="hover:text-indigo-600 transition-colors">{{ $hotel['nama'] }}</a></li>
            <li><i class="fa fa-chevron-right text-xs mx-1"></i></li>
            <li class="text-indigo-600 font-medium">Pemesanan</li>
        </ol>
    </nav>
</div>

{{-- PROGRESS BAR --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
    <div class="flex items-center justify-center gap-0">
        <div class="flex flex-col items-center">
            <div class="w-10 h-10 rounded-full gradient-hero text-white flex items-center justify-center font-bold shadow-lg">1</div>
            <span class="text-xs font-semibold text-indigo-600 mt-1">Isi Data</span>
        </div>
        <div class="h-1 w-20 bg-gradient-to-r from-indigo-500 to-purple-300 rounded mx-1"></div>
        <div class="flex flex-col items-center">
            <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center font-bold">2</div>
            <span class="text-xs text-gray-400 mt-1">Pembayaran</span>
        </div>
        <div class="h-1 w-20 bg-gray-200 rounded mx-1"></div>
        <div class="flex flex-col items-center">
            <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center font-bold">3</div>
            <span class="text-xs text-gray-400 mt-1">Konfirmasi</span>
        </div>
    </div>
</div>

{{-- MAIN CONTENT --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
    @if (isset($errors) && $errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4 flex items-start gap-3">
        <i class="fa fa-circle-exclamation text-red-500 mt-0.5"></i>
        <ul class="text-sm text-red-700 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- LEFT: Hotel Summary + Price Summary --}}
        <div class="lg:col-span-1 space-y-6">

            {{-- Hotel Card --}}
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                <div class="relative h-52 overflow-hidden">
                    <img src="{{ $hotel['foto'] }}" alt="{{ $hotel['nama'] }}"
                         class="w-full h-full object-cover"
                         onerror="this.src='/images/placeholder.svg'">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4">
                        @if($hotel['starRating'])
                        <div class="flex gap-0.5 mb-1">
                            @for ($s = 1; $s <= 5; $s++)
                                <i class="fa fa-star text-xs {{ $s <= $hotel['starRating'] ? 'text-yellow-400' : 'text-gray-400' }}"></i>
                            @endfor
                        </div>
                        @endif
                        <h2 class="text-white font-bold text-lg leading-tight">{{ $hotel['nama'] }}</h2>
                    </div>
                </div>
                <div class="p-5 space-y-3">
                    <div class="flex items-start gap-2 text-gray-600">
                        <i class="fa fa-location-dot text-indigo-500 mt-0.5 flex-shrink-0"></i>
                        <span class="text-sm">{{ $hotel['alamat'] }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-green-600 font-medium">
                        <i class="fa fa-shield-check"></i>
                        <span>Pembayaran aman & terjamin</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-indigo-600 font-medium">
                        <i class="fa fa-clock-rotate-left"></i>
                        <span>Pembatalan gratis hingga 24 jam</span>
                    </div>
                </div>
            </div>

            {{-- Price Summary --}}
            <div class="bg-white rounded-3xl shadow-xl p-6 sticky top-24">
                <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2">
                    <i class="fa fa-receipt text-indigo-500"></i> Ringkasan Harga
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600">Tipe Kamar</span>
                        <span class="font-semibold text-gray-800" id="summary-room-type">–</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600">Harga per malam</span>
                        <span class="font-semibold text-gray-800" id="summary-price-night">Rp 0</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600">Jumlah malam</span>
                        <span class="font-semibold text-gray-800" id="summary-nights">0 malam</span>
                    </div>
                    <div class="border-t border-dashed border-gray-200 pt-3 mt-2 flex justify-between items-center">
                        <span class="font-bold text-gray-800">Subtotal</span>
                        <span class="font-extrabold text-indigo-600 text-xl" id="summary-total">Rp 0</span>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-3"><i class="fa fa-info-circle mr-1"></i>Harga belum termasuk pajak 10%</p>
            </div>
        </div>

        {{-- RIGHT: Booking Form --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-xl p-8">
                <h1 class="text-2xl font-extrabold text-gray-900 mb-1">Detail Pemesanan</h1>
                <p class="text-gray-500 text-sm mb-8">Lengkapi data berikut untuk melanjutkan ke pembayaran</p>

                <form action="{{ route('booking.store') }}" method="POST" id="bookingForm">
                    @csrf
                    <input type="hidden" name="hotel_id" value="{{ $id }}">
                    <input type="hidden" name="hotel_name" value="{{ $hotel['nama'] }}">
                    <input type="hidden" name="hotel_foto" value="{{ $hotel['foto'] }}">
                    <input type="hidden" name="room_price" id="hidden_room_price" value="">

                    {{-- Room Type --}}
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fa fa-bed text-indigo-400 mr-1"></i> Tipe Kamar <span class="text-red-500">*</span>
                        </label>
                        @if(!empty($hotel['kamar']))
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="room-cards">
                            @foreach($hotel['kamar'] as $kamar)
                            <label class="room-card cursor-pointer border-2 border-gray-200 rounded-2xl p-4 hover:border-indigo-400 transition-all"
                                   data-price="{{ $kamar['harga'] }}" data-type="{{ $kamar['tipe'] }}">
                                <input type="radio" name="room_type" value="{{ $kamar['tipe'] }}"
                                       class="sr-only" {{ old('room_type') == $kamar['tipe'] ? 'checked' : '' }}>
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $kamar['tipe'] }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            <i class="fa fa-user mr-1"></i> Maks {{ $kamar['kapasitas'] }} tamu
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-indigo-600 font-bold text-sm">Rp {{ number_format($kamar['harga'], 0, ',', '.') }}</p>
                                        <p class="text-xs text-gray-400">/malam</p>
                                    </div>
                                </div>
                                <div class="mt-2 hidden check-mark">
                                    <span class="text-xs text-indigo-600 font-semibold"><i class="fa fa-check-circle mr-1"></i>Dipilih</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @else
                        <input type="text" name="room_type" value="{{ old('room_type') }}"
                               placeholder="Contoh: Deluxe Room"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:border-indigo-500 transition-colors"
                               required>
                        @endif
                    </div>

                    {{-- Dates & Guests with Real-time 14:00 Checkin & 12:00 Checkout Standards --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                        <div class="bg-indigo-50/60 dark:bg-slate-800/60 p-3 rounded-2xl border border-indigo-100 dark:border-slate-700">
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-bold uppercase tracking-wider text-indigo-700 dark:text-indigo-400 flex items-center gap-1">
                                    <i class="fa fa-calendar-check"></i> Check-in <span class="text-red-500">*</span>
                                </label>
                                <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full bg-indigo-600 text-white">14.00 WIB</span>
                            </div>
                            <input type="date" name="checkin" id="checkin" value="{{ old('checkin', $checkin) }}"
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full border border-indigo-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm focus:border-indigo-500 transition-colors bg-white dark:bg-slate-900 dark:text-white font-medium" required>
                            <span class="text-[11px] text-gray-500 dark:text-gray-400 block mt-1">Waktu masuk mulai jam 2 siang</span>
                        </div>

                        <div class="bg-purple-50/60 dark:bg-slate-800/60 p-3 rounded-2xl border border-purple-100 dark:border-slate-700">
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-bold uppercase tracking-wider text-purple-700 dark:text-purple-400 flex items-center gap-1">
                                    <i class="fa fa-calendar-xmark"></i> Check-out <span class="text-red-500">*</span>
                                </label>
                                <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full bg-purple-600 text-white">12.00 WIB</span>
                            </div>
                            <input type="date" name="checkout" id="checkout" value="{{ old('checkout', $checkout) }}"
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   class="w-full border border-purple-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm focus:border-purple-500 transition-colors bg-white dark:bg-slate-900 dark:text-white font-medium" required>
                            <span class="text-[11px] text-gray-500 dark:text-gray-400 block mt-1">Waktu keluar maksimal jam 12 siang</span>
                        </div>

                        <div class="bg-slate-50 dark:bg-slate-800/60 p-3 rounded-2xl border border-slate-200 dark:border-slate-700">
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 flex items-center gap-1">
                                    <i class="fa fa-users text-indigo-400"></i> Tamu <span class="text-red-500">*</span>
                                </label>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200">Kapasitas</span>
                            </div>
                            <input type="number" name="guests" id="guests" value="{{ old('guests', $guests) }}"
                                   min="1" max="20"
                                   class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-3 py-2 text-sm focus:border-indigo-500 transition-colors bg-white dark:bg-slate-900 dark:text-white font-medium" required>
                            <span class="text-[11px] text-gray-500 dark:text-gray-400 block mt-1">Dewasa & anak-anak</span>
                        </div>
                    </div>

                    {{-- Guest Data with KTP/NIK Requirement --}}
                    <div class="border-t border-gray-100 dark:border-slate-800 pt-6 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base font-bold text-gray-800 dark:text-white flex items-center gap-2">
                                <i class="fa fa-user-circle text-indigo-500"></i> Data Pemesan (Identitas KTP/NIK)
                            </h3>
                            <span class="text-xs text-indigo-600 dark:text-indigo-400 font-semibold bg-indigo-50 dark:bg-indigo-950/50 px-2.5 py-1 rounded-full border border-indigo-200 dark:border-indigo-800">
                                <i class="fa-solid fa-lock mr-1"></i> Data Terenkripsi
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Nama Lengkap (Sesuai KTP) <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" value="{{ old('name', Auth::user()?->name ?? '') }}"
                                       placeholder="Masukkan nama lengkap sesuai kartu identitas"
                                       class="w-full border border-gray-300 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:border-indigo-500 transition-colors dark:bg-slate-800 dark:text-white" required>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                    <i class="fa-solid fa-id-card text-indigo-500 mr-1"></i> Nomor KTP / NIK (16 Digit) <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="no_ktp" id="no_ktp" value="{{ old('no_ktp') }}" maxlength="16" minlength="16" pattern="[0-9]{16}"
                                       placeholder="Contoh: 1271012345678901"
                                       class="w-full border border-gray-300 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:border-indigo-500 transition-colors font-mono tracking-wider dark:bg-slate-800 dark:text-white" required>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1">
                                    <i class="fa-solid fa-info-circle text-indigo-400"></i> Wajib 16 digit angka NIK KTP untuk verifikasi identitas resmi saat check-in di resepsionis hotel.
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Email Aktif <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email', Auth::user()?->email ?? '') }}"
                                       placeholder="email@contoh.com"
                                       class="w-full border border-gray-300 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:border-indigo-500 transition-colors dark:bg-slate-800 dark:text-white" required>
                                <span class="text-[11px] text-gray-400">E-tiket & bukti pemesanan dikirim ke sini</span>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nomor Telepon / WhatsApp <span class="text-red-500">*</span></label>
                                <input type="tel" name="phone" value="{{ old('phone') }}"
                                       placeholder="08xxxxxxxxxx"
                                       class="w-full border border-gray-300 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:border-indigo-500 transition-colors dark:bg-slate-800 dark:text-white" required>
                                <span class="text-[11px] text-gray-400">Untuk koordinasi check-in cepat</span>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Permintaan Khusus <span class="text-gray-400 font-normal">(opsional)</span></label>
                                <textarea name="special_requests" rows="3"
                                          placeholder="Contoh: Kamar bebas asap rokok, lantai tinggi, check-in lebih awal jika tersedia, dsb."
                                          class="w-full border border-gray-300 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:border-indigo-500 transition-colors resize-none dark:bg-slate-800 dark:text-white">{{ old('special_requests') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Terms --}}
                    <div class="border-t border-gray-100 pt-6 mb-8">
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <input type="checkbox" name="terms" id="terms" required
                                   class="mt-0.5 w-5 h-5 accent-indigo-600 cursor-pointer">
                            <span class="text-sm text-gray-600 group-hover:text-gray-800 transition-colors">
                                Saya menyetujui <a href="#" class="text-indigo-600 hover:underline font-medium">Syarat & Ketentuan</a>
                                dan <a href="#" class="text-indigo-600 hover:underline font-medium">Kebijakan Privasi</a> Roomify.
                                Saya memahami kebijakan pembatalan yang berlaku.
                            </span>
                        </label>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" id="submitBtn"
                            class="w-full gradient-hero text-white font-bold py-4 rounded-2xl text-base shadow-xl hover:opacity-90 hover:shadow-2xl transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="fa fa-credit-card"></i>
                        Lanjut ke Pembayaran
                        <i class="fa fa-arrow-right ml-1"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Room card selection
    const roomCards  = document.querySelectorAll('.room-card');
    const hiddenPrice = document.getElementById('hidden_room_price');

    function updatePrice() {
        const checkin  = document.getElementById('checkin').value;
        const checkout = document.getElementById('checkout').value;
        const selected = document.querySelector('.room-card.selected');

        let nights = 0;
        if (checkin && checkout) {
            const d1 = new Date(checkin), d2 = new Date(checkout);
            nights = Math.max(0, Math.round((d2 - d1) / 86400000));
        }

        document.getElementById('summary-nights').textContent = nights + ' malam';

        if (selected) {
            const price  = parseInt(selected.dataset.price) || 0;
            const type   = selected.dataset.type;
            const total  = price * nights;
            document.getElementById('summary-room-type').textContent   = type;
            document.getElementById('summary-price-night').textContent = 'Rp ' + price.toLocaleString('id-ID');
            document.getElementById('summary-total').textContent       = 'Rp ' + total.toLocaleString('id-ID');
            hiddenPrice.value = price;
        }
    }

    roomCards.forEach(card => {
        card.addEventListener('click', () => {
            roomCards.forEach(c => {
                c.classList.remove('selected', 'border-indigo-500', 'bg-indigo-50');
                c.classList.add('border-gray-200');
                c.querySelector('.check-mark')?.classList.add('hidden');
                c.querySelector('input[type=radio]').checked = false;
            });
            card.classList.add('selected', 'border-indigo-500', 'bg-indigo-50');
            card.classList.remove('border-gray-200');
            card.querySelector('.check-mark')?.classList.remove('hidden');
            card.querySelector('input[type=radio]').checked = true;
            updatePrice();
        });
    });

    // Auto-select first room
    if (roomCards.length > 0) roomCards[0].click();

    document.getElementById('checkin').addEventListener('change', function () {
        const ci = new Date(this.value);
        ci.setDate(ci.getDate() + 1);
        const minCo = ci.toISOString().split('T')[0];
        const coEl  = document.getElementById('checkout');
        coEl.min = minCo;
        if (coEl.value && coEl.value <= this.value) coEl.value = minCo;
        updatePrice();
    });

    document.getElementById('checkout').addEventListener('change', updatePrice);

    // Trigger initial calculation if dates pre-filled
    updatePrice();
</script>
</body>
</html>
