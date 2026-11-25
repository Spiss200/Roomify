<!-- Premium Live System Clock & Smart Time-Theme Controller -->
<div class="bg-slate-950 text-slate-300 text-[11px] py-1.5 px-3 sm:px-6 border-b border-slate-800/90 select-none shadow-inner">
    <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-2.5">

        <!-- Left: Live Clock & WIB Time Status -->
        <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-500/15 text-emerald-400 font-extrabold text-[10px] tracking-wider border border-emerald-500/30">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>WAKTU SISTEM</span>
            </span>

            <div class="flex items-center gap-1.5 text-white font-mono text-[11px] font-semibold tracking-tight">
                <i class="fa-regular fa-clock text-blue-400 text-[10px]"></i>
                <span id="live-system-time-display">Memuat waktu...</span>
            </div>

            <!-- Time-of-day dynamic pill -->
            <span id="time-period-indicator" class="hidden md:inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-950/80 text-blue-300 border border-blue-800/60">
                <i id="time-period-icon" class="fa-solid fa-sun text-amber-400 text-[9px]"></i>
                <span id="time-period-text">Siang Hari (06:00 - 18:00 WIB)</span>
            </span>
        </div>

        <!-- Right: Segmented Theme Control (Otomatis / Terang / Gelap) -->
        <div class="flex items-center gap-2">
            <span class="hidden lg:inline text-[10px] font-semibold text-slate-400 uppercase tracking-wider">
                Tema:
            </span>

            <div class="inline-flex items-center rounded-lg bg-slate-900 p-0.5 border border-slate-800" role="group" aria-label="Mode Tema">
                <!-- Auto (Jam) -->
                <button type="button"
                        id="theme-btn-auto"
                        onclick="setThemeMode('auto')"
                        title="Mode Otomatis: Terang jam 06:00–18:00 WIB, Gelap jam 18:00–06:00 WIB"
                        class="px-2.5 py-1 rounded-md text-[10px] font-bold transition flex items-center gap-1.5 cursor-pointer text-slate-400 hover:text-white">
                    <i class="fa-solid fa-wand-magic-sparkles text-[9px] text-indigo-400"></i>
                    <span>Otomatis (Jam)</span>
                </button>

                <!-- Manual Light -->
                <button type="button"
                        id="theme-btn-light"
                        onclick="setThemeMode('light')"
                        title="Paksa Mode Terang (Siang)"
                        class="px-2 py-1 rounded-md text-[10px] font-bold transition flex items-center gap-1 cursor-pointer text-slate-400 hover:text-white">
                    <i class="fa-solid fa-sun text-[9px] text-amber-400"></i>
                    <span class="hidden sm:inline">Terang</span>
                </button>

                <!-- Manual Dark -->
                <button type="button"
                        id="theme-btn-dark"
                        onclick="setThemeMode('dark')"
                        title="Paksa Mode Gelap (Malam)"
                        class="px-2 py-1 rounded-md text-[10px] font-bold transition flex items-center gap-1 cursor-pointer text-slate-400 hover:text-white">
                    <i class="fa-solid fa-moon text-[9px] text-blue-300"></i>
                    <span class="hidden sm:inline">Gelap</span>
                </button>
            </div>
        </div>

    </div>
</div>

<script>
    // Universal Smart Theme & Real-Time Clock Controller for Roomify
    (function() {
        // Calculate exact WIB hour (UTC+7) regardless of client machine timezone
        window.getWibHour = function() {
            try {
                const str = new Intl.DateTimeFormat('en-US', {
                    timeZone: 'Asia/Jakarta',
                    hour: 'numeric',
                    hour12: false
                }).format(new Date());
                const h = parseInt(str, 10);
                return isNaN(h) ? new Date().getHours() : h;
            } catch (e) {
                const d = new Date();
                return (d.getUTCHours() + 7) % 24;
            }
        };

        window.getAutoTimeTheme = function() {
            const h = window.getWibHour();
            // 06:00 - 17:59 WIB = Mode Terang, 18:00 - 05:59 WIB = Mode Gelap
            return (h >= 6 && h < 18) ? 'light' : 'dark';
        };

        window.getRoomifyThemeMode = function() {
            return localStorage.getItem('theme_mode') || 'auto';
        };

        window.setThemeMode = function(mode) {
            localStorage.setItem('theme_mode', mode);
            window.applyRoomifyTheme();
        };

        window.cycleThemeMode = function() {
            const current = window.getRoomifyThemeMode();
            let next = 'auto';
            if (current === 'auto') {
                next = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
            } else if (current === 'light') {
                next = 'dark';
            } else if (current === 'dark') {
                next = 'auto';
            }
            window.setThemeMode(next);
        };

        window.applyRoomifyTheme = function() {
            const mode = window.getRoomifyThemeMode();
            const wibHour = window.getWibHour();
            const isDay = (wibHour >= 6 && wibHour < 18);
            const autoTheme = isDay ? 'light' : 'dark';

            let effective = (mode === 'auto') ? autoTheme : mode;

            if (effective === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            // Update segmented buttons visual state
            const btnAuto = document.getElementById('theme-btn-auto');
            const btnLight = document.getElementById('theme-btn-light');
            const btnDark = document.getElementById('theme-btn-dark');

            const activeClass = 'bg-blue-600 text-white shadow-xs font-black';
            const inactiveClass = 'text-slate-400 hover:text-white';

            if (btnAuto && btnLight && btnDark) {
                btnAuto.className = `px-2.5 py-1 rounded-md text-[10px] transition flex items-center gap-1.5 cursor-pointer ${mode === 'auto' ? activeClass : inactiveClass}`;
                btnLight.className = `px-2 py-1 rounded-md text-[10px] transition flex items-center gap-1 cursor-pointer ${mode === 'light' ? activeClass : inactiveClass}`;
                btnDark.className = `px-2 py-1 rounded-md text-[10px] transition flex items-center gap-1 cursor-pointer ${mode === 'dark' ? activeClass : inactiveClass}`;

                if (mode === 'auto') {
                    btnAuto.innerHTML = `<i class="fa-solid fa-wand-magic-sparkles text-[9px] text-amber-300"></i><span>Otomatis (${isDay ? 'Siang ☀️' : 'Malam 🌙'})</span>`;
                } else {
                    btnAuto.innerHTML = `<i class="fa-solid fa-wand-magic-sparkles text-[9px] text-indigo-400"></i><span>Otomatis (Jam)</span>`;
                }
            }

            // Update Time-of-day period indicator
            const periodText = document.getElementById('time-period-text');
            const periodIcon = document.getElementById('time-period-icon');
            if (periodText && periodIcon) {
                if (isDay) {
                    periodText.textContent = `Siang Hari (06:00 - 18:00 WIB) • Mode Terang`;
                    periodIcon.className = 'fa-solid fa-sun text-amber-400 text-[9px]';
                } else {
                    periodText.textContent = `Malam Hari (18:00 - 06:00 WIB) • Mode Gelap`;
                    periodIcon.className = 'fa-solid fa-moon text-blue-300 text-[9px]';
                }
            }
        };

        function updateLiveClockWidget() {
            const el = document.getElementById('live-system-time-display');
            const now = new Date();

            // Format date and time in WIB (UTC+7)
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

            // Get Jakarta date/time components
            let parts;
            try {
                const formatter = new Intl.DateTimeFormat('id-ID', {
                    timeZone: 'Asia/Jakarta',
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                });
                parts = formatter.format(now).replace(/\./g, ':');
            } catch (e) {
                const dayName = days[now.getDay()];
                const dayNum = String(now.getDate()).padStart(2, '0');
                const monthName = months[now.getMonth()];
                const year = now.getFullYear();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                parts = `${dayName}, ${dayNum} ${monthName} ${year} • ${hours}:${minutes}:${seconds}`;
            }

            if (el) {
                el.textContent = `${parts} WIB`;
            }

            // Auto-check theme if mode is 'auto'
            if (window.getRoomifyThemeMode() === 'auto') {
                const effective = window.getAutoTimeTheme();
                const currentIsDark = document.documentElement.classList.contains('dark');
                if ((effective === 'dark' && !currentIsDark) || (effective === 'light' && currentIsDark)) {
                    window.applyRoomifyTheme();
                }
            }
        }

        window.applyRoomifyTheme();
        updateLiveClockWidget();
        setInterval(updateLiveClockWidget, 1000);
    })();
</script>
