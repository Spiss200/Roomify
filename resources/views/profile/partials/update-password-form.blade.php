<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <!-- Current Password -->
        <div>
            <label for="update_password_current_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5 flex items-center gap-1.5">
                <i class="fa-solid fa-lock text-amber-500 text-xs"></i>
                <span>Kata Sandi Saat Ini</span>
                <span class="text-rose-500">*</span>
            </label>
            <input id="update_password_current_password"
                   name="current_password"
                   type="password"
                   class="w-full px-4 py-3 rounded-xl border-2 border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white placeholder-slate-400 text-sm font-medium focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-500/20 shadow-xs transition"
                   autocomplete="current-password"
                   placeholder="••••••••"
                   required />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1.5 text-xs text-rose-500" />
        </div>

        <!-- New Password -->
        <div>
            <label for="update_password_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5 flex items-center gap-1.5">
                <i class="fa-solid fa-key text-amber-500 text-xs"></i>
                <span>Kata Sandi Baru</span>
                <span class="text-rose-500">*</span>
            </label>
            <input id="update_password_password"
                   name="password"
                   type="password"
                   class="w-full px-4 py-3 rounded-xl border-2 border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white placeholder-slate-400 text-sm font-medium focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-500/20 shadow-xs transition"
                   autocomplete="new-password"
                   placeholder="Minimal 8 karakter..."
                   required />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1.5 text-xs text-rose-500" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="update_password_password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5 flex items-center gap-1.5">
                <i class="fa-solid fa-shield-check text-amber-500 text-xs"></i>
                <span>Konfirmasi Kata Sandi Baru</span>
                <span class="text-rose-500">*</span>
            </label>
            <input id="update_password_password_confirmation"
                   name="password_confirmation"
                   type="password"
                   class="w-full px-4 py-3 rounded-xl border-2 border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white placeholder-slate-400 text-sm font-medium focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-500/20 shadow-xs transition"
                   autocomplete="new-password"
                   placeholder="Ketik ulang kata sandi baru..."
                   required />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1.5 text-xs text-rose-500" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-100 text-white dark:text-slate-900 font-extrabold text-xs shadow-md transition flex items-center gap-2">
                <i class="fa-solid fa-lock"></i>
                <span>Perbarui Kata Sandi</span>
            </button>
        </div>
    </form>
</section>
