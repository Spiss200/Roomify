<x-app-layout>
    <x-slot name="title">
        Pengaturan Profil & Personalisasi Akun – Roomify
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">

        <!-- Flash Status Message -->
        @if (session('status') === 'profile-updated')
            <div class="mb-6 p-4 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-700 dark:text-emerald-300 flex items-center justify-between text-xs font-bold shadow-sm">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-sm"></i>
                    <span>Informasi profil akun Anda berhasil disimpan dan diperbarui!</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if (session('status') === 'password-updated')
            <div class="mb-6 p-4 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-700 dark:text-emerald-300 flex items-center justify-between text-xs font-bold shadow-sm">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-sm"></i>
                    <span>Kata sandi akun Anda berhasil diperbarui dengan aman!</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        <!-- Profile Hero Header -->
        <div class="rounded-3xl p-6 sm:p-8 mb-8 text-white relative overflow-hidden shadow-xl"
             style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #4f46e5 80%, #7c3aed 100%);">
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-white/10 border border-white/20 backdrop-blur-md text-white flex items-center justify-center font-black text-2xl shadow-inner shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                                {{ $user->name }}
                            </h1>
                            @if($user->isAdmin())
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase bg-purple-400 text-slate-900 shadow flex items-center gap-1">
                                    <i class="fa-solid fa-crown text-[10px]"></i> Administrator
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase bg-amber-400 text-slate-900 shadow flex items-center gap-1">
                                    <i class="fa-solid fa-user-check text-[10px]"></i> Member
                                </span>
                            @endif
                        </div>
                        <p class="text-xs sm:text-sm text-indigo-200 mt-1 flex items-center gap-2">
                            <i class="fa-solid fa-envelope text-xs opacity-75"></i> {{ $user->email }}
                            <span>•</span>
                            <span>Bergabung sejak {{ $user->created_at->format('d M Y') }}</span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2.5">
                    @if($user->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs shadow flex items-center gap-1.5 transition">
                            <i class="fa-solid fa-shield-halved"></i>
                            <span>Admin Console</span>
                        </a>
                    @endif
                    <a href="{{ route('dashboard') }}" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/20 shadow flex items-center gap-1.5 transition">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>Kembali ke Dashboard</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="space-y-6">

            <!-- Card 1: Informasi Profil -->
            <div class="p-6 sm:p-8 bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm transition-colors">
                <div class="max-w-2xl">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-slate-700">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/80 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-lg shrink-0">
                            <i class="fa-solid fa-id-card"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-slate-900 dark:text-white">
                                Informasi Profil & Data Diri
                            </h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                Perbarui nama akun dan alamat email yang Anda gunakan untuk menerima tiket reservasi hotel.
                            </p>
                        </div>
                    </div>

                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Card 2: Keamanan Kata Sandi -->
            <div class="p-6 sm:p-8 bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm transition-colors">
                <div class="max-w-2xl">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-slate-700">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/80 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg shrink-0">
                            <i class="fa-solid fa-key"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-slate-900 dark:text-white">
                                Keamanan & Kata Sandi
                            </h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                Pastikan akun Anda tetap aman dengan menggunakan kata sandi acak yang panjang dan unik.
                            </p>
                        </div>
                    </div>

                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Card 3: Zona Berbahaya -->
            <div class="p-6 sm:p-8 bg-white dark:bg-slate-800 rounded-3xl border border-rose-200 dark:border-rose-950/60 shadow-sm transition-colors">
                <div class="max-w-2xl">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-slate-700">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/80 text-rose-600 dark:text-rose-400 flex items-center justify-center text-lg shrink-0">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-rose-600 dark:text-rose-400">
                                Zona Penghapusan Akun
                            </h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                Setelah akun Anda dihapus, semua data profil dan e-voucher Anda akan dihapus secara permanen.
                            </p>
                        </div>
                    </div>

                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
