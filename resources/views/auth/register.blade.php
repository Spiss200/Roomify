<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Daftar Akun Roomify</h2>
        <p class="text-xs text-slate-500 mt-1">Buat akun untuk memesan hotel impian di Kota Medan</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                <i class="fa-solid fa-user text-indigo-500 mr-1"></i> Nama Lengkap
            </label>
            <input id="name"
                   type="text"
                   name="name"
                   value="{{ old('name') }}"
                   required autofocus autocomplete="name"
                   placeholder="Contoh: Bintang Pamungkas"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                <i class="fa-solid fa-envelope text-indigo-500 mr-1"></i> Alamat Email
            </label>
            <input id="email"
                   type="email"
                   name="email"
                   value="{{ old('email') }}"
                   required autocomplete="username"
                   placeholder="nama@email.com"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                <i class="fa-solid fa-lock text-indigo-500 mr-1"></i> Kata Sandi
            </label>
            <input id="password"
                   type="password"
                   name="password"
                   required autocomplete="new-password"
                   placeholder="Minimal 8 karakter"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                <i class="fa-solid fa-shield-check text-indigo-500 mr-1"></i> Konfirmasi Kata Sandi
            </label>
            <input id="password_confirmation"
                   type="password"
                   name="password_confirmation"
                   required autocomplete="new-password"
                   placeholder="Ketik ulang kata sandi"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" class="btn-gradient w-full py-3.5 rounded-2xl text-white font-extrabold text-sm flex items-center justify-center gap-2">
                <i class="fa-solid fa-user-plus"></i>
                <span>Daftar Akun Sekarang</span>
            </button>
        </div>

        <!-- Already registered -->
        <div class="text-center pt-3 border-t border-slate-100">
            <p class="text-xs text-slate-500">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-800 transition">
                    Masuk ke Akun
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
