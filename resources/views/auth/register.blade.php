<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Daftar Akun Roomify</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Buat akun untuk memesan hotel impian di Kota Medan</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                <i class="fa-solid fa-user text-blue-500 mr-1"></i> Nama Lengkap
            </label>
            <input id="name"
                   type="text"
                   name="name"
                   value="{{ old('name') }}"
                   required autofocus autocomplete="name"
                   placeholder="Contoh: Bintang Pamungkas"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                <i class="fa-solid fa-envelope text-blue-500 mr-1"></i> Alamat Email
            </label>
            <input id="email"
                   type="email"
                   name="email"
                   value="{{ old('email') }}"
                   required autocomplete="username"
                   placeholder="nama@email.com"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                <i class="fa-solid fa-lock text-blue-500 mr-1"></i> Kata Sandi
            </label>
            <input id="password"
                   type="password"
                   name="password"
                   required autocomplete="new-password"
                   placeholder="Minimal 8 karakter"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                <i class="fa-solid fa-shield-check text-blue-500 mr-1"></i> Konfirmasi Kata Sandi
            </label>
            <input id="password_confirmation"
                   type="password"
                   name="password_confirmation"
                   required autocomplete="new-password"
                   placeholder="Ketik ulang kata sandi"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" class="btn-gradient w-full py-3.5 rounded-2xl text-white font-extrabold text-sm flex items-center justify-center gap-2 cursor-pointer">
                <i class="fa-solid fa-user-plus"></i>
                <span>Daftar Akun Sekarang</span>
            </button>
        </div>

        <!-- Already registered -->
        <div class="text-center pt-3 border-t border-slate-100 dark:border-slate-800">
            <p class="text-xs text-slate-500 dark:text-slate-400">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-bold text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition">
                    Masuk ke Akun
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
