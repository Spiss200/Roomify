<x-app-layout>
    <x-slot name="title">
        Admin Console – Roomify Medan
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">

        <!-- Flash Notifications -->
        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-700 dark:text-emerald-300 flex items-center justify-between text-xs font-bold shadow-sm">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-sm"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-2xl bg-rose-500/15 border border-rose-500/30 text-rose-700 dark:text-rose-300 flex items-center justify-between text-xs font-bold shadow-sm">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        <!-- Admin Header Banner -->
        <div class="rounded-3xl p-6 sm:p-8 mb-8 text-white relative overflow-hidden shadow-xl"
             style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #312e81 100%);">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 text-xs font-bold mb-3 border border-indigo-500/30">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span>Panel Administrasi Roomify</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Pusat Kendali Admin & Manajemen Akun
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-2xl leading-relaxed">
                        Pantau seluruh data akun pengguna, lacak destinasi pesanan hotel ("kemana mereka memesan"), kelola transaksi reservasi, dan moderasi ulasan rating hotel di Kota Medan.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ url('/search') }}" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/20 transition flex items-center gap-1.5">
                        <i class="fa-solid fa-hotel"></i>
                        <span>Lihat Semua Hotel</span>
                    </a>
                    <a href="{{ route('dashboard') }}" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-md transition flex items-center gap-1.5">
                        <i class="fa-solid fa-user"></i>
                        <span>Dashboard Saya</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 4 Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="p-5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-slate-900 dark:text-white">{{ $totalUsers }}</div>
                    <div class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Pengguna</div>
                </div>
            </div>

            <div class="p-5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-slate-900 dark:text-white">{{ $totalBookings }}</div>
                    <div class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ $paidBookings }} Lunas · {{ $pendingBookings }} Pending</div>
                </div>
            </div>

            <div class="p-5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
                <div>
                    <div class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                    <div class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Pendapatan</div>
                </div>
            </div>

            <div class="p-5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-city"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-slate-900 dark:text-white">187</div>
                    <div class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Hotel Aktif di Medan</div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex items-center gap-2 border-b border-slate-200 dark:border-slate-800 mb-6 overflow-x-auto pb-1 text-xs">
            <a href="{{ route('admin.dashboard', ['tab' => 'overview']) }}" class="px-4 py-2.5 rounded-xl font-bold flex items-center gap-2 transition {{ $tab === 'overview' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                <i class="fa-solid fa-users-gear"></i>
                <span>Data Akun & Tujuan Pesanan</span>
            </a>
            <a href="{{ route('admin.dashboard', ['tab' => 'bookings']) }}" class="px-4 py-2.5 rounded-xl font-bold flex items-center gap-2 transition {{ $tab === 'bookings' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                <i class="fa-solid fa-calendar-check"></i>
                <span>Kelola Reservasi Booking ({{ $totalBookings }})</span>
            </a>
            <a href="{{ route('admin.dashboard', ['tab' => 'moderation']) }}" class="px-4 py-2.5 rounded-xl font-bold flex items-center gap-2 transition {{ $tab === 'moderation' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                <i class="fa-solid fa-star-half-stroke"></i>
                <span>Moderasi Rating & Review Hotel</span>
            </a>
        </div>

        <!-- TAB 1: DATA AKUN & TUJUAN PESANAN ("kemana dia") -->
        @if($tab === 'overview')
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 dark:border-slate-700/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Daftar Akun Pengguna & Riwayat Pemesanan</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Periksa siapa saja pengguna yang terdaftar dan kemana saja hotel yang mereka pesan.</p>
                    </div>
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ $users->total() }} Akun Terdaftar</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900/60 border-b border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 font-bold uppercase text-[10px] tracking-wider">
                                <th class="py-3.5 px-4">Pengguna</th>
                                <th class="py-3.5 px-4">Role / Akses</th>
                                <th class="py-3.5 px-4">Terdaftar</th>
                                <th class="py-3.5 px-4">Tujuan Pesanan Hotel ("Kemana Dia")</th>
                                <th class="py-3.5 px-4 text-right">Tindakan Admin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 text-slate-700 dark:text-slate-200">
                            @forelse($users as $user)
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition">
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-indigo-100 dark:bg-indigo-950/80 text-indigo-700 dark:text-indigo-300 font-black flex items-center justify-center shrink-0">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-extrabold text-slate-900 dark:text-white">{{ $user->name }}</div>
                                                <div class="text-[11px] text-slate-400">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        @if($user->isAdmin())
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-purple-100 dark:bg-purple-950/80 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                                                <i class="fa-solid fa-crown text-[9px]"></i> Administrator
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300">
                                                <i class="fa-solid fa-user text-[9px]"></i> Member
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-slate-500 dark:text-slate-400">
                                        {{ $user->created_at->format('d M Y') }}
                                    </td>
                                    <td class="py-4 px-4">
                                        @if($user->bookings->count() > 0)
                                            <div class="space-y-1">
                                                @foreach($user->bookings->take(2) as $b)
                                                    <div class="flex items-center gap-1.5 text-xs">
                                                        <span class="w-2 h-2 rounded-full {{ $b->payment_status === 'paid' ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                                        <strong class="font-bold text-slate-900 dark:text-white truncate max-w-[180px]">{{ $b->hotel_name }}</strong>
                                                        <span class="text-[10px] text-slate-400">({{ $b->checkin_date }})</span>
                                                    </div>
                                                @endforeach
                                                @if($user->bookings->count() > 2)
                                                    <span class="text-[10px] text-indigo-500 font-semibold">+{{ $user->bookings->count() - 2 }} pesanan lainnya</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-slate-400 text-[11px] italic">Belum ada pesanan</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-right">
                                        <div class="inline-flex items-center gap-2">
                                            @if($user->id !== auth()->id())
                                                <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="px-2.5 py-1.5 rounded-lg border text-[11px] font-bold transition {{ $user->isAdmin() ? 'border-amber-300 text-amber-600 hover:bg-amber-50 dark:border-amber-700 dark:text-amber-400' : 'border-indigo-300 text-indigo-600 hover:bg-indigo-50 dark:border-indigo-700 dark:text-indigo-400' }}" title="Ubah hak akses akun">
                                                        {{ $user->isAdmin() ? 'Cabut Admin' : 'Jadikan Admin' }}
                                                    </button>
                                                </form>

                                                <form action="{{ route('admin.users.delete', $user) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus akun {{ $user->name }}? Tindakan ini permanen.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/50 transition" title="Hapus akun">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-[11px] font-bold text-slate-400 italic">Akun Anda</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400">Belum ada akun terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                    <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        @endif

        <!-- TAB 2: KELOLA RESERVASI BOOKING -->
        @if($tab === 'bookings')
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 dark:border-slate-700/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Daftar Transaksi Reservasi Hotel</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Pantau tiket kode reservasi, NIK KTP tamu, serta verifikasi status pembayaran.</p>
                    </div>

                    <!-- Search Filter Form -->
                    <form action="{{ route('admin.dashboard') }}" method="GET" class="flex items-center gap-2">
                        <input type="hidden" name="tab" value="bookings">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode booking / nama / NIK..." class="px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <button type="submit" class="px-3 py-1.5 rounded-xl bg-indigo-600 text-white font-bold text-xs hover:bg-indigo-700 transition">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900/60 border-b border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 font-bold uppercase text-[10px] tracking-wider">
                                <th class="py-3.5 px-4">Kode Booking</th>
                                <th class="py-3.5 px-4">Tamu & Kontak</th>
                                <th class="py-3.5 px-4">Hotel & Kamar</th>
                                <th class="py-3.5 px-4">Jadwal Menginap</th>
                                <th class="py-3.5 px-4">Total & Status</th>
                                <th class="py-3.5 px-4 text-right">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 text-slate-700 dark:text-slate-200">
                            @forelse($bookings as $b)
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition">
                                    <td class="py-4 px-4 font-mono font-bold text-indigo-600 dark:text-indigo-400">
                                        {{ $b->booking_code }}
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="font-extrabold text-slate-900 dark:text-white">{{ $b->guest_name }}</div>
                                        <div class="text-[11px] text-slate-400">KTP: {{ $b->no_ktp ?? '-' }}</div>
                                        <div class="text-[11px] text-slate-400">{{ $b->guest_phone ?? '-' }}</div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="font-bold text-slate-900 dark:text-white">{{ $b->hotel_name }}</div>
                                        <div class="text-[11px] text-slate-400">{{ $b->room_type }}</div>
                                    </td>
                                    <td class="py-4 px-4 text-slate-500 dark:text-slate-400 text-[11px]">
                                        <div>Check-In: <strong>{{ $b->checkin_date }}</strong> (14:00)</div>
                                        <div>Check-Out: <strong>{{ $b->checkout_date }}</strong> (12:00)</div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="font-black text-slate-900 dark:text-white">Rp {{ number_format($b->total_price, 0, ',', '.') }}</div>
                                        @if($b->payment_status === 'paid')
                                            <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                                                ✅ Lunas
                                            </span>
                                        @elseif($b->payment_status === 'pending')
                                            <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300">
                                                ⏳ Menunggu
                                            </span>
                                        @else
                                            <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300">
                                                ❌ Dibatalkan
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-right">
                                        <div class="inline-flex items-center gap-1.5">
                                            @if($b->payment_status !== 'paid')
                                                <form action="{{ route('admin.bookings.status', $b) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="paid">
                                                    <button type="submit" class="px-2 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-[10px] transition" title="Tandai Sudah Lunas">
                                                        Set Lunas
                                                    </button>
                                                </form>
                                            @endif

                                            @if($b->payment_status !== 'cancelled')
                                                <form action="{{ route('admin.bookings.status', $b) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit" class="px-2 py-1 rounded-lg bg-amber-600 hover:bg-amber-500 text-white font-bold text-[10px] transition" title="Batalkan Reservasi">
                                                        Batal
                                                    </button>
                                                </form>
                                            @endif

                                            <form action="{{ route('admin.bookings.delete', $b) }}" method="POST" onsubmit="return confirm('Hapus data pesanan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/50 transition" title="Hapus Permanen">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-400">Tidak ada data reservasi yang cocok.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($bookings->hasPages())
                    <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                        {{ $bookings->links() }}
                    </div>
                @endif
            </div>
        @endif

        <!-- TAB 3: MODERASI RATING & REVIEW HOTEL -->
        @if($tab === 'moderation')
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Moderasi Rating & Ulasan Hotel</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Kelola ulasan tamu yang tidak pantas, reset rating ulasan bermasalah, atau hapus komentar palsu.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($hotelsList as $h)
                        <div class="p-4 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/40 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-bold px-2 py-0.5 rounded-md bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300">
                                        ⭐ {{ $h['rating'] ?? '4.5' }} / 5.0
                                    </span>
                                    <span class="text-[11px] text-slate-400">{{ $h['review_count'] ?? 120 }} Ulasan</span>
                                </div>
                                <h4 class="font-extrabold text-slate-900 dark:text-white text-sm">{{ $h['name'] ?? 'Hotel' }}</h4>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-1">{{ $h['address'] ?? 'Kota Medan' }}</p>
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-200 dark:border-slate-700/80 flex items-center justify-between gap-2">
                                <a href="{{ url('/hotel/' . ($h['id'] ?? 1)) }}" target="_blank" class="text-[11px] font-bold text-indigo-600 hover:text-indigo-700">
                                    Lihat Detail →
                                </a>
                                <form action="{{ route('admin.reviews.delete', $h['id'] ?? 1) }}" method="POST" onsubmit="return confirm('Hapus ulasan atau reset rating hotel ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400 text-[10px] font-bold transition flex items-center gap-1">
                                        <i class="fa-solid fa-trash-can text-[9px]"></i> Hapus Ulasan
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-400 text-xs">Belum ada data ulasan.</p>
                    @endforelse
                </div>
            </div>
        @endif

    </div>
</x-app-layout>
