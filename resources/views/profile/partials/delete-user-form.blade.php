<section class="space-y-4">
    <p class="text-xs text-slate-500 dark:text-slate-400">
        Sebelum menghapus akun, mohon pastikan Anda telah mencatat atau menyelesaikan seluruh reservasi kamar hotel aktif Anda.
    </p>

    <button type="button"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow transition flex items-center gap-2">
        <i class="fa-solid fa-trash-can"></i>
        <span>Hapus Akun Saya</span>
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-white dark:bg-slate-900 rounded-3xl">
            @csrf
            @method('delete')

            <h2 class="text-base font-extrabold text-slate-900 dark:text-white">
                Apakah Anda yakin ingin menghapus akun Anda?
            </h2>

            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                Setelah akun Anda dihapus, seluruh data pesanan, e-voucher, dan riwayat hotel Anda akan dihapus permanen. Masukkan kata sandi akun Anda untuk mengonfirmasi penghapusan.
            </p>

            <div class="mt-5">
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                    Kata Sandi Akun
                </label>
                <input id="password"
                       name="password"
                       type="password"
                       class="w-full px-4 py-3 rounded-xl border-2 border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-rose-600 focus:ring-4 focus:ring-rose-500/20"
                       placeholder="Masukkan kata sandi untuk konfirmasi..." />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1.5 text-xs text-rose-500" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button"
                        x-on:click="$dispatch('close')"
                        class="px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                    Batal
                </button>

                <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow transition">
                    Ya, Hapus Akun Saya
                </button>
            </div>
        </form>
    </x-modal>
</section>
