<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <!-- Name Input -->
        <div>
            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5 flex items-center gap-1.5">
                <i class="fa-solid fa-user text-indigo-500 text-xs"></i>
                <span>Nama Lengkap</span>
                <span class="text-rose-500">*</span>
            </label>
            <input id="name"
                   name="name"
                   type="text"
                   class="w-full px-4 py-3 rounded-xl border-2 border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white placeholder-slate-400 text-sm font-medium focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-500/20 shadow-xs transition"
                   value="{{ old('name', $user->name) }}"
                   placeholder="Masukkan nama lengkap Anda..."
                   required
                   autofocus
                   autocomplete="name" />
            <x-input-error class="mt-1.5 text-xs text-rose-500" :messages="$errors->get('name')" />
        </div>

        <!-- Email Input -->
        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5 flex items-center gap-1.5">
                <i class="fa-solid fa-envelope text-indigo-500 text-xs"></i>
                <span>Alamat Email</span>
                <span class="text-rose-500">*</span>
            </label>
            <input id="email"
                   name="email"
                   type="email"
                   class="w-full px-4 py-3 rounded-xl border-2 border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white placeholder-slate-400 text-sm font-medium focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-500/20 shadow-xs transition"
                   value="{{ old('email', $user->email) }}"
                   placeholder="nama@email.com"
                   required
                   autocomplete="username" />
            <x-input-error class="mt-1.5 text-xs text-rose-500" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2.5 p-3 rounded-xl bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800 text-xs text-amber-800 dark:text-amber-200">
                    <p>
                        Email Anda belum diverifikasi.
                        <button form="send-verification" class="underline font-bold hover:text-amber-900 dark:hover:text-amber-100">
                            Klik di sini untuk mengirim ulang email verifikasi.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1.5 font-bold text-emerald-600 dark:text-emerald-400">
                            Link verifikasi baru telah dikirim ke alamat email Anda.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="btn-gradient px-6 py-2.5 rounded-xl text-white font-extrabold text-xs shadow-md flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </form>
</section>
