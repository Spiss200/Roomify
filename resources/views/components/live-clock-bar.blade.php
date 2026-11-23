<!-- Slim Live System Clock Top Bar with Smart Time-Theme Controller -->
<div class="bg-slate-950 text-slate-400 text-[11px] py-1 px-4 sm:px-6 border-b border-slate-800/80 transition-colors select-none">
    <div class="max-w-7xl mx-auto flex items-center justify-between gap-3 flex-wrap">
        <!-- Live System Clock Indicator -->
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-emerald-500/15 text-emerald-400 font-bold text-[10px] tracking-wider border border-emerald-500/25">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>WAKTU SISTEM</span>
            </span>
            <div class="flex items-center gap-1.5 text-slate-200 font-mono text-[11px] tracking-tight">
                <i class="fa-regular fa-clock text-blue-400 text-[10px]"></i>
                <span id="live-system-time-display">Memuat waktu...</span>
            </div>
        </div>

        <!-- Right Side: Auto Theme & Timezone Tag -->
        <div class="flex items-center gap-2.5 text-[11px] text-slate-400">
            <button type="button"
                    onclick="cycleThemeMode()"
                    id="auto-theme-badge"
                    title="Klik untuk mengubah tema (Otomatis / Terang / Gelap)"
                    class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-slate-900 hover:bg-slate-800 text-slate-300 text-[10px] font-medium border border-slate-700/80 transition cursor-pointer">
                <i id="theme-status-icon" class="fa-solid fa-circle-half-stroke text-amber-400 text-[10px]"></i>
                <span id="auto-theme-label">Mode Otomatis</span>
            </button>
            <span class="hidden sm:inline text-slate-700">•</span>
            <span class="text-slate-400 text-[10px] font-medium hidden sm:inline">Kota Medan (WIB)</span>
        </div>
    </div>
</div>

<script>
    // Universal Theme & Clock Controller for Roomify
    (function() {
        if (localStorage.getItem('theme_user_override') === 'true' && !localStorage.getItem('theme_mode')) {
            localStorage.removeItem('theme_user_override');
            localStorage.setItem('theme_mode', 'auto');
        }

        function getAutoTimeTheme() {
            const hour = new Date().getHours();
            // 06:00 - 17:59 WIB = Mode Terang, 18:00 - 05:59 WIB = Mode Gelap
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
                    iconEl.className = isDay ? 'fa-solid fa-sun text-amber-400 text-[10px]' : 'fa-solid fa-moon text-blue-300 text-[10px]';
                } else if (mode === 'light') {
                    labelEl.textContent = 'Manual: Terang ☀️';
                    iconEl.className = 'fa-solid fa-sun text-amber-400 text-[10px]';
                } else {
                    labelEl.textContent = 'Manual: Gelap 🌙';
                    iconEl.className = 'fa-solid fa-moon text-blue-300 text-[10px]';
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

            if (window.getRoomifyThemeMode() === 'auto') {
                window.applyRoomifyTheme();
            }
        }

        window.applyRoomifyTheme();
        updateLiveClockWidget();
        setInterval(updateLiveClockWidget, 1000);
    })();
</script>
