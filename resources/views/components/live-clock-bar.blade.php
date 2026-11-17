<!-- Live System Clock Top Bar with Smart Time-Theme Controller -->
<div class="bg-slate-900/95 dark:bg-slate-950 text-slate-300 text-xs py-2 px-4 shadow-xs border-b border-white/10 transition-colors">
    <div class="max-w-7xl mx-auto flex items-center justify-between gap-2 flex-wrap">
        <!-- Live System Clock Indicator -->
        <div class="flex items-center gap-2.5">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 font-extrabold text-[10px] tracking-wider border border-emerald-500/30">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> WAKTU SISTEM
            </span>
            <div class="flex items-center gap-2 text-white font-mono font-bold text-xs tracking-wide">
                <i class="fa-regular fa-clock text-indigo-400 text-xs"></i>
                <span id="live-system-time-display">Memuat waktu...</span>
            </div>
        </div>

        <!-- Right Side: Auto Theme & Timezone Tag -->
        <div class="flex items-center gap-2.5 text-[11px] text-slate-400">
            <button type="button"
                    onclick="cycleThemeMode()"
                    id="auto-theme-badge"
                    title="Klik untuk mengubah mode (Otomatis Waktu / Terang / Gelap)"
                    class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-white/10 hover:bg-white/20 text-slate-200 text-[10px] font-semibold border border-white/15 transition cursor-pointer">
                <i id="theme-status-icon" class="fa-solid fa-circle-half-stroke text-amber-400"></i>
                <span id="auto-theme-label">Mode Otomatis</span>
            </button>
            <span class="hidden sm:inline text-slate-500">•</span>
            <span class="text-slate-400 text-[11px] font-medium hidden sm:inline">Kota Medan (WIB)</span>
        </div>
    </div>
</div>

<script>
    // Universal Theme & Clock Controller for Roomify
    (function() {
        // Clear any old legacy locks
        if (localStorage.getItem('theme_user_override') === 'true' && !localStorage.getItem('theme_mode')) {
            localStorage.removeItem('theme_user_override');
            localStorage.setItem('theme_mode', 'auto');
        }

        function getAutoTimeTheme() {
            const hour = new Date().getHours();
            // 06:00 - 17:59 WIB = Mode Terang (Siang), 18:00 - 05:59 WIB = Mode Gelap (Malam)
            return (hour >= 6 && hour < 18) ? 'light' : 'dark';
        }

        window.getRoomifyThemeMode = function() {
            return localStorage.getItem('theme_mode') || 'auto';
        };

        window.applyRoomifyTheme = function() {
            const mode = window.getRoomifyThemeMode();
            let effectiveTheme = 'light';
            if (mode === 'light') {
                effectiveTheme = 'light';
            } else if (mode === 'dark') {
                effectiveTheme = 'dark';
            } else {
                effectiveTheme = getAutoTimeTheme();
            }

            if (effectiveTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            // Update badge UI
            const labelEl = document.getElementById('auto-theme-label');
            const iconEl = document.getElementById('theme-status-icon');
            if (labelEl && iconEl) {
                const hour = new Date().getHours();
                const isDay = (hour >= 6 && hour < 18);
                if (mode === 'auto') {
                    labelEl.textContent = isDay ? 'Auto: Siang ☀️' : 'Auto: Malam 🌙';
                    iconEl.className = isDay ? 'fa-solid fa-sun text-amber-400' : 'fa-solid fa-moon text-indigo-300';
                } else if (mode === 'light') {
                    labelEl.textContent = 'Manual: Terang ☀️';
                    iconEl.className = 'fa-solid fa-sun text-amber-400';
                } else {
                    labelEl.textContent = 'Manual: Gelap 🌙';
                    iconEl.className = 'fa-solid fa-moon text-indigo-300';
                }
            }
        };

        window.cycleThemeMode = function() {
            const currentMode = window.getRoomifyThemeMode();
            let nextMode = 'auto';
            if (currentMode === 'auto') {
                nextMode = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
            } else if (currentMode === 'light') {
                nextMode = 'dark';
            } else if (currentMode === 'dark') {
                nextMode = 'auto';
            }
            localStorage.setItem('theme_mode', nextMode);
            localStorage.setItem('theme', (nextMode === 'auto' ? getAutoTimeTheme() : nextMode));
            window.applyRoomifyTheme();
        };

        window.toggleTheme = function() {
            // Navbar toggle button cycles theme
            window.cycleThemeMode();
        };

        function updateLiveClockWidget() {
            const el = document.getElementById('live-system-time-display');
            if (!el) return;
            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

            const dayName = days[now.getDay()];
            const dayNum = String(now.getDate()).padStart(2, '0');
            const monthName = months[now.getMonth()];
            const year = now.getFullYear();

            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            el.textContent = `${dayName}, ${dayNum} ${monthName} ${year} · ${hours}:${minutes}:${seconds} WIB`;

            // If in auto mode, verify theme every second
            if (window.getRoomifyThemeMode() === 'auto') {
                window.applyRoomifyTheme();
            }
        }

        // Apply immediately
        window.applyRoomifyTheme();
        updateLiveClockWidget();
        setInterval(updateLiveClockWidget, 1000);
    })();
</script>
