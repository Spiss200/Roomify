<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Selamat Datang Kembali</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Masuk ke akun Roomify Anda untuk mengelola pemesanan</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                <i class="fa-solid fa-envelope text-blue-500 mr-1"></i> Alamat Email
            </label>
            <input id="email"
                   type="email"
                   name="email"
                   value="{{ old('email') }}"
                   required autofocus autocomplete="username"
                   placeholder="nama@email.com"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1">
                <label for="password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                    <i class="fa-solid fa-lock text-blue-500 mr-1"></i> Kata Sandi
                </label>
                @if (Route::has('password.request'))
                    <a class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition" href="{{ route('password.request') }}">
                        Lupa sandi?
                    </a>
                @endif
            </div>
            <input id="password"
                   type="password"
                   name="password"
                   required autocomplete="current-password"
                   placeholder="Masukkan kata sandi"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between text-xs text-slate-600 dark:text-slate-400 pt-1">
            <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 dark:border-slate-600 text-blue-600 shadow-sm focus:ring-blue-500 dark:bg-slate-800" name="remember">
                <span>Ingat saya</span>
            </label>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" class="btn-gradient w-full py-3.5 rounded-2xl text-white font-extrabold text-sm flex items-center justify-center gap-2 cursor-pointer">
                <i class="fa-solid fa-right-to-bracket"></i>
                <span>Masuk Sekarang</span>
            </button>
        </div>

        <!-- Register Link -->
        <div class="text-center pt-3 border-t border-slate-100 dark:border-slate-800">
            <p class="text-xs text-slate-500 dark:text-slate-400">
                Belum memiliki akun?
                <a href="{{ route('register') }}" class="font-bold text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition">
                    Daftar Akun Baru
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
