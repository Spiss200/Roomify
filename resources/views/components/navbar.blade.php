@props(['active' => ''])

<header class="sticky top-0 z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800 transition-colors shadow-xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 group shrink-0 no-underline">
                <x-application-logo class="w-9 h-9 drop-shadow-xs transition transform group-hover:scale-105" />
                <div class="flex items-center gap-2">
                    <span class="text-xl font-bold tracking-tight text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">
                        Roomify
                    </span>
                    <span class="hidden sm:inline-block text-[10px] font-bold uppercase tracking-wider text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-950/70 border border-blue-200/80 dark:border-blue-800/80 px-2 py-0.5 rounded-full">
                        Medan
                    </span>
                </div>
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center gap-1">
                <a href="{{ route('home') }}"
                   class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition {{ $active === 'home' ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/60 font-bold' : 'text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800/60' }}">
                    <i class="fa-solid fa-house mr-1.5 text-xs {{ $active === 'home' ? 'text-blue-600 dark:text-blue-400' : 'text-slate-400' }}"></i>
                    Beranda
                </a>
                <a href="{{ route('search') }}"
                   class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition {{ $active === 'search' ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/60 font-bold' : 'text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800/60' }}">
                    <i class="fa-solid fa-magnifying-glass mr-1.5 text-xs {{ $active === 'search' ? 'text-blue-600 dark:text-blue-400' : 'text-slate-400' }}"></i>
                    Cari Hotel
                </a>
                <a href="{{ route('landmark.index') }}"
                   class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition {{ $active === 'landmarks' ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/60 font-bold' : 'text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800/60' }}">
                    <i class="fa-solid fa-landmark mr-1.5 text-xs {{ $active === 'landmarks' ? 'text-blue-600 dark:text-blue-400' : 'text-slate-400' }}"></i>
                    Landmark Medan
                </a>
                <a href="{{ route('semantik.index') }}"
                   class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition {{ $active === 'semantik' ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/60 font-bold' : 'text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800/60' }}">
                    <i class="fa-solid fa-diagram-project mr-1.5 text-xs {{ $active === 'semantik' ? 'text-blue-600 dark:text-blue-400' : 'text-slate-400' }}"></i>
                    Web Semantik
                </a>
            </nav>

            <!-- Right Actions -->
            <div class="flex items-center gap-2.5">
                <!-- Theme Switcher Toggle -->
                <button type="button"
                        onclick="cycleThemeMode()"
                        class="w-9 h-9 rounded-xl flex items-center justify-center text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 transition"
                        title="Ganti Tema">
                    <i class="fa-solid fa-moon hidden dark:inline text-amber-300 text-sm"></i>
                    <i class="fa-solid fa-sun inline dark:hidden text-amber-500 text-sm"></i>
                </button>

                @auth
                    <!-- Authenticated User Profile Dropdown / Link -->
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center gap-2 px-3 py-2 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                        <i class="fa-solid fa-circle-user text-blue-600 dark:text-blue-400 text-base"></i>
                        <span class="max-w-[120px] truncate hidden sm:inline">{{ Auth::user()->name }}</span>
                    </a>
                @else
                    <!-- Guest Login & Register Buttons -->
                    <a href="{{ route('login') }}"
                       class="text-xs sm:text-sm font-semibold text-slate-600 hover:text-blue-600 dark:text-slate-300 dark:hover:text-white px-3 py-2 transition hidden sm:inline-block">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-xl shadow-xs hover:shadow-sm transition">
                        <i class="fa-solid fa-user-plus text-xs"></i>
                        <span>Daftar</span>
                    </a>
                @endauth

                <!-- Mobile Hamburger Toggle -->
                <button type="button"
                        onclick="document.getElementById('mobile-global-nav').classList.toggle('hidden')"
                        class="md:hidden w-9 h-9 flex items-center justify-center rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition"
                        aria-label="Menu navigasi">
                    <i class="fa-solid fa-bars text-base"></i>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Drawer Menu -->
    <div id="mobile-global-nav" class="hidden md:hidden border-t border-slate-200 dark:border-slate-800 bg-white/98 dark:bg-slate-900/98 px-4 py-3 space-y-1.5 shadow-xl backdrop-blur-md">
        <a href="{{ route('home') }}" class="block px-3 py-2 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">
            <i class="fa-solid fa-house mr-2 text-blue-500"></i> Beranda
        </a>
        <a href="{{ route('search') }}" class="block px-3 py-2 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">
            <i class="fa-solid fa-magnifying-glass mr-2 text-blue-500"></i> Cari Hotel
        </a>
        <a href="{{ route('landmark.index') }}" class="block px-3 py-2 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">
            <i class="fa-solid fa-landmark mr-2 text-blue-500"></i> Landmark Medan
        </a>
        <a href="{{ route('semantik.index') }}" class="block px-3 py-2 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">
            <i class="fa-solid fa-diagram-project mr-2 text-blue-500"></i> Web Semantik
        </a>
        <div class="pt-2 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between gap-2">
            @auth
                <a href="{{ route('dashboard') }}" class="w-full text-center py-2 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 font-bold text-xs">
                    Dashboard ({{ Auth::user()->name }})
                </a>
            @else
                <a href="{{ route('login') }}" class="w-1/2 text-center py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 font-semibold text-xs">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="w-1/2 text-center py-2 rounded-xl bg-blue-600 text-white font-bold text-xs shadow-xs">
                    Daftar
                </a>
            @endauth
        </div>
    </div>
</header>
