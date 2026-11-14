<x-app-layout>
    <x-slot name="title">
        Dashboard Member – Roomify
    </x-slot>

    <div class="py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">

        <!-- Welcome Banner -->
        <div class="rounded-3xl p-6 sm:p-8 mb-8 text-white relative overflow-hidden shadow-xl"
             style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #4f46e5 80%, #7c3aed 100%);">
            <!-- Decorative blurred circles -->
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-pink-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute left-1/3 -top-10 w-64 h-64 bg-indigo-400/20 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-white/10 border border-white/20 backdrop-blur-md text-white flex items-center justify-center font-black text-2xl shadow-inner">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">
                                Halo, {{ Auth::user()->name }}! 👋
                            </h1>
                            @if(Auth::user()->isAdmin())
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase bg-purple-400 text-slate-900 shadow flex items-center gap-1">
                                    <i class="fa-solid fa-crown text-[10px]"></i> Administrator
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase bg-amber-400 text-slate-900 shadow flex items-center gap-1">
                                    <i class="fa-solid fa-user-check text-[10px]"></i> Member Aktif
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-indigo-200 mt-1 flex items-center gap-2">
                            <i class="fa-solid fa-envelope text-xs opacity-75"></i> {{ Auth::user()->email }}
                            <span>•</span>
                            <i class="fa-solid fa-shield-halved text-xs text-emerald-400"></i> Akun Terverifikasi
                        </p>
                    </div>
                </div>

                <!-- Quick Action Buttons -->
                <div class="flex items-center gap-2.5 flex-wrap">
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2.5 rounded-2xl bg-purple-600 hover:bg-purple-500 text-white text-xs font-black shadow flex items-center gap-1.5 transition">
                            <i class="fa-solid fa-shield-halved"></i>
                            <span>Admin Console</span>
                        </a>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="px-4 py-2.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 shadow flex items-center gap-1.5 transition">
                        <i class="fa-solid fa-user-pen"></i>
                        <span>Edit Profil</span>
                    </a>
                    <a href="{{ url('/search') }}" class="btn-gradient text-white text-xs font-bold px-4 py-2.5 rounded-2xl flex items-center gap-1.5 shadow-lg">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span>Cari Hotel</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 4 Highlights Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-4 transition-colors">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-hotel"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-slate-800 dark:text-white">187</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 font-semibold uppercase">Hotel Terdaftar</div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-4 transition-colors">
                <div class="w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-landmark"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-slate-800 dark:text-white">13</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 font-semibold uppercase">Landmark Ikonik</div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-4 transition-colors">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-slate-800 dark:text-white">{{ $bookings->count() }}</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 font-semibold uppercase">Pesanan Selesai</div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-4 transition-colors">
                <div class="w-12 h-12 rounded-xl bg-pink-50 dark:bg-pink-950/60 text-pink-600 dark:text-pink-400 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-slate-800 dark:text-white">GPS</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 font-semibold uppercase">Google Maps Rute</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">

            <!-- Left: Riwayat Pemesanan (Database Bookings) -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Riwayat Pemesanan Permanen -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-7 border border-slate-200 dark:border-slate-700 shadow-sm transition-colors">
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-clock-rotate-left text-indigo-600 dark:text-indigo-400 text-lg"></i>
                            <h2 class="text-lg font-black text-slate-800 dark:text-white">Riwayat Pemesanan Saya</h2>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-indigo-50 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300">
                            {{ $bookings->count() }} Transaksi Tersimpan
                        </span>
                    </div>

                    @if($bookings->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($bookings as $item)
                                <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 p-5 hover:border-indigo-300 dark:hover:border-indigo-600 transition">
                                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 border-b border-slate-200 dark:border-slate-700/80 pb-4">
                                        <div class="flex gap-4">
                                            @if($item->hotel_foto)
                                                <img src="{{ asset($item->hotel_foto) }}" alt="{{ $item->hotel_name }}"
                                                     class="w-20 h-20 rounded-xl object-cover shrink-0 border border-slate-200 dark:border-slate-700"
                                                     onerror="this.src='/images/placeholder.svg'">
                                            @endif
                                            <div>
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="font-mono text-xs font-black text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/70 px-2 py-0.5 rounded-md border border-indigo-200 dark:border-indigo-800">
                                                        {{ $item->booking_code }}
                                                    </span>
                                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 uppercase flex items-center gap-1">
                                                        <i class="fa-solid fa-check-circle"></i> Terkonfirmasi & Lunas
                                                    </span>
                                                </div>
                                                <h3 class="text-base font-extrabold text-slate-900 dark:text-white">{{ $item->hotel_name }}</h3>
                                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                                    Tipe: <strong class="text-slate-700 dark:text-slate-300">{{ $item->room_type }}</strong> • {{ $item->nights }} Malam • {{ $item->guests }} Tamu
                                                </p>
                                            </div>
                                        </div>

                                        <div class="text-right shrink-0">
                                            <span class="text-[10px] text-slate-400 dark:text-slate-400 block font-medium">Total Dibayar</span>
                                            <span class="text-lg font-black text-indigo-600 dark:text-indigo-400">
                                                Rp {{ number_format($item->total_price, 0, ',', '.') }}
                                            </span>
                                            <span class="text-[11px] text-slate-400 block uppercase font-mono">{{ $item->payment_method ?? 'VA' }}</span>
                                        </div>
                                    </div>

                                    <!-- Details row -->
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-3 text-xs">
                                        <div class="bg-white dark:bg-slate-800 p-2.5 rounded-xl border border-slate-100 dark:border-slate-700">
                                            <span class="text-slate-400 dark:text-slate-400 block text-[10px] uppercase font-bold">Check-in (14.00)</span>
                                            <span class="font-bold text-slate-800 dark:text-white">{{ $item->checkin_date ? $item->checkin_date->format('d M Y') : '-' }}</span>
                                            <span class="text-[10px] text-indigo-600 dark:text-indigo-400 block font-semibold">{{ $item->checkin_time ?? '14:00 WIB' }}</span>
                                        </div>
                                        <div class="bg-white dark:bg-slate-800 p-2.5 rounded-xl border border-slate-100 dark:border-slate-700">
                                            <span class="text-slate-400 dark:text-slate-400 block text-[10px] uppercase font-bold">Check-out (12.00)</span>
                                            <span class="font-bold text-slate-800 dark:text-white">{{ $item->checkout_date ? $item->checkout_date->format('d M Y') : '-' }}</span>
                                            <span class="text-[10px] text-purple-600 dark:text-purple-400 block font-semibold">{{ $item->checkout_time ?? '12:00 WIB' }}</span>
                                        </div>
                                        <div class="bg-white dark:bg-slate-800 p-2.5 rounded-xl border border-slate-100 dark:border-slate-700">
                                            <span class="text-slate-400 dark:text-slate-400 block text-[10px] uppercase font-bold">NIK KTP Pemesan</span>
                                            <span class="font-mono font-bold text-slate-800 dark:text-white text-[11px]">{{ substr($item->no_ktp, 0, 6) }}******{{ substr($item->no_ktp, -4) }}</span>
                                            <span class="text-[10px] text-emerald-600 dark:text-emerald-400 block font-semibold">Terverifikasi</span>
                                        </div>
                                        <div class="bg-white dark:bg-slate-800 p-2.5 rounded-xl border border-slate-100 dark:border-slate-700 flex flex-col justify-center">
                                            <a href="{{ route('hotel.detail', ['id' => $item->hotel_id]) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline font-bold text-center text-xs flex items-center justify-center gap-1">
                                                <i class="fa-solid fa-map-pin text-[10px]"></i> Info & Rute →
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Sesi Pemesanan Aktif jika ada di session -->
                        @if(session('booking'))
                            @php $b = session('booking'); @endphp
                            <div class="rounded-2xl p-5 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-950/40 dark:to-purple-950/40 border border-indigo-100 dark:border-indigo-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                                <div>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 uppercase">Menunggu Pembayaran</span>
                                    <h3 class="text-base font-extrabold text-slate-900 dark:text-white mt-2">{{ $b['hotel_name'] ?? 'Pemesanan Kamar' }}</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Tipe: <strong class="text-slate-700 dark:text-slate-200">{{ $b['room_type'] ?? 'Standard' }}</strong> • {{ $b['nights'] ?? 1 }} Malam</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Check-in: <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $b['checkin'] ?? '-' }} (14:00)</span> s/d <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $b['checkout'] ?? '-' }} (12:00)</span></p>
                                </div>
                                <div class="text-right sm:border-l sm:border-indigo-100 dark:sm:border-indigo-800 sm:pl-5 flex flex-col justify-between">
                                    <div>
                                        <span class="text-[10px] text-slate-400 block font-medium">Subtotal</span>
                                        <span class="text-lg font-black text-indigo-600 dark:text-indigo-400">Rp {{ number_format($b['subtotal'] ?? 0, 0, ',', '.') }}</span>
                                    </div>
                                    <a href="{{ route('payment.show') }}" class="mt-3 btn-gradient text-white text-xs font-bold px-4 py-2 rounded-xl text-center">
                                        Lanjut Bayar →
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="rounded-2xl border border-dashed border-slate-200 dark:border-slate-700 p-8 text-center bg-slate-50/50 dark:bg-slate-900/30">
                                <div class="w-14 h-14 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-500 flex items-center justify-center text-2xl mx-auto mb-3">
                                    <i class="fa-solid fa-bed"></i>
                                </div>
                                <h3 class="font-extrabold text-slate-800 dark:text-white text-sm">Belum Ada Riwayat Pemesanan</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto mt-1 leading-relaxed">
                                    Setelah memesan hotel dan melakukan konfirmasi pembayaran, tiket dan nomor pesanan Anda akan otomatis tersimpan di akun ini.
                                </p>
                                <a href="{{ url('/search') }}" class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition shadow-sm">
                                    <i class="fa-solid fa-compass"></i> Cari Hotel Sekarang
                                </a>
                            </div>
                        @endif
                    @endif
                </div>

                <!-- Rekomendasi Hotel Pilihan Medan -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-7 border border-slate-200 dark:border-slate-700 shadow-sm transition-colors">
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-star text-amber-500 text-lg"></i>
                            <h2 class="text-lg font-black text-slate-800 dark:text-white">Hotel Terpopuler di Medan</h2>
                        </div>
                        <a href="{{ url('/search') }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                            Lihat Semua <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @php
                            $rekomendasi = [
                                [
                                    'id' => 'hotel-005',
                                    'nama' => 'Adimulia Hotel Medan',
                                    'alamat' => 'Jl. Diponegoro No. 8, Petisah',
                                    'star' => 5,
                                    'rating' => 4.8,
                                    'harga' => 1250000,
                                    'foto' => '/images/hotels/adimulia-hotel-medan.jpg',
                                    'landmark' => 'Sun Plaza (350 m)'
                                ],
                                [
                                    'id' => 'hotel-001',
                                    'nama' => "d'primahotel Kualanamu",
                                    'alamat' => 'Jl. Sultan Serdang No. 88',
                                    'star' => 4,
                                    'rating' => 4.5,
                                    'harga' => 514240,
                                    'foto' => '/images/hotels/dprimahotelkualanamumedan/1.jpg',
                                    'landmark' => 'Bandara & Railink'
                                ]
                            ];
                        @endphp
                        @foreach($rekomendasi as $h)
                            <div class="rounded-2xl border border-slate-100 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-900/50 p-4 flex gap-3 hover:border-indigo-200 dark:hover:border-indigo-600 transition group">
                                <img src="{{ asset($h['foto']) }}" alt="{{ $h['nama'] }}" class="w-20 h-20 rounded-xl object-cover shrink-0" onerror="this.src='/images/placeholder.svg'">
                                <div class="flex-1 min-w-0 flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center gap-1 text-amber-500 text-[10px] font-bold">
                                            <i class="fa-solid fa-star"></i> {{ $h['star'] }} Bintang • ⭐ {{ $h['rating'] }}
                                        </div>
                                        <h4 class="font-extrabold text-slate-900 dark:text-white text-sm truncate group-hover:text-indigo-600 transition">
                                            {{ $h['nama'] }}
                                        </h4>
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate">
                                            <i class="fa-solid fa-route text-indigo-400"></i> {{ $h['landmark'] }}
                                        </p>
                                    </div>
                                    <div class="flex items-center justify-between pt-1">
                                        <span class="text-xs font-black text-indigo-600 dark:text-indigo-400">Rp {{ number_format($h['harga'], 0, ',', '.') }}</span>
                                        <a href="{{ route('hotel.detail', ['id' => $h['id']]) }}" class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                                            Pilih →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- Right Column: Landmark Shortcuts & Profil -->
            <div class="space-y-6">

                <!-- Profil Singkat -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm transition-colors">
                    <h3 class="font-black text-slate-800 dark:text-white text-sm uppercase tracking-wider mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-user-circle text-indigo-500"></i> Informasi Akun
                    </h3>
                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-700">
                            <span class="text-slate-400">Nama Pengguna</span>
                            <span class="font-bold text-slate-800 dark:text-white">{{ Auth::user()->name }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-700">
                            <span class="text-slate-400">Email Akun</span>
                            <span class="font-bold text-slate-800 dark:text-white">{{ Auth::user()->email }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-700">
                            <span class="text-slate-400">Penyimpanan Riwayat</span>
                            <span class="font-semibold text-emerald-600 bg-emerald-50 dark:bg-emerald-950 px-2 py-0.5 rounded">Database SQLite</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-slate-400">Terdaftar Pada</span>
                            <span class="font-bold text-slate-800 dark:text-white">{{ Auth::user()->created_at ? Auth::user()->created_at->format('d M Y') : 'Hari ini' }}</span>
                        </div>
                    </div>

                    <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-700">
                        <a href="{{ route('profile.edit') }}" class="w-full py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-gear text-slate-400"></i> Edit Pengaturan Profil
                        </a>
                    </div>
                </div>

                <!-- Landmark Populer Cepat -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm transition-colors">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-black text-slate-800 dark:text-white text-sm uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-indigo-500"></i> Landmark Medan
                        </h3>
                        <span class="text-[10px] font-bold text-slate-400">13 Lokasi</span>
                    </div>
                    <div class="space-y-2">
                        <a href="{{ url('/search?landmark=Istana+Maimun') }}" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-indigo-50 dark:hover:bg-slate-700 transition text-xs font-semibold text-slate-700 dark:text-slate-200 group no-underline">
                            <span class="flex items-center gap-2">
                                <span>🏛️</span>
                                <span class="group-hover:text-indigo-600 dark:group-hover:text-indigo-400">Istana Maimun</span>
                            </span>
                            <i class="fa-solid fa-arrow-right text-[10px] text-slate-300 group-hover:text-indigo-600 group-hover:translate-x-1 transition"></i>
                        </a>
                        <a href="{{ url('/search?landmark=Tjong+A+Fie+Mansion') }}" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-indigo-50 dark:hover:bg-slate-700 transition text-xs font-semibold text-slate-700 dark:text-slate-200 group no-underline">
                            <span class="flex items-center gap-2">
                                <span>🏮</span>
                                <span class="group-hover:text-indigo-600 dark:group-hover:text-indigo-400">Tjong A Fie Mansion</span>
                            </span>
                            <i class="fa-solid fa-arrow-right text-[10px] text-slate-300 group-hover:text-indigo-600 group-hover:translate-x-1 transition"></i>
                        </a>
                        <a href="{{ url('/search?landmark=Masjid+Raya+Al+Mashun') }}" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-indigo-50 dark:hover:bg-slate-700 transition text-xs font-semibold text-slate-700 dark:text-slate-200 group no-underline">
                            <span class="flex items-center gap-2">
                                <span>🕌</span>
                                <span class="group-hover:text-indigo-600 dark:group-hover:text-indigo-400">Masjid Raya Al-Mashun</span>
                            </span>
                            <i class="fa-solid fa-arrow-right text-[10px] text-slate-300 group-hover:text-indigo-600 group-hover:translate-x-1 transition"></i>
                        </a>
                        <a href="{{ url('/search?landmark=Lapangan+Merdeka') }}" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-indigo-50 dark:hover:bg-slate-700 transition text-xs font-semibold text-slate-700 dark:text-slate-200 group no-underline">
                            <span class="flex items-center gap-2">
                                <span>🌳</span>
                                <span class="group-hover:text-indigo-600 dark:group-hover:text-indigo-400">Lapangan Merdeka (KM 0)</span>
                            </span>
                            <i class="fa-solid fa-arrow-right text-[10px] text-slate-300 group-hover:text-indigo-600 group-hover:translate-x-1 transition"></i>
                        </a>
                        <a href="{{ url('/search?landmark=Sun+Plaza') }}" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-indigo-50 dark:hover:bg-slate-700 transition text-xs font-semibold text-slate-700 dark:text-slate-200 group no-underline">
                            <span class="flex items-center gap-2">
                                <span>🛍️</span>
                                <span class="group-hover:text-indigo-600 dark:group-hover:text-indigo-400">Sun Plaza Medan</span>
                            </span>
                            <i class="fa-solid fa-arrow-right text-[10px] text-slate-300 group-hover:text-indigo-600 group-hover:translate-x-1 transition"></i>
                        </a>
                    </div>
                </div>

            </div>

        </div>

    </div>
</x-app-layout>
