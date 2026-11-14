<!-- Live System Clock Top Bar (Simplified & Elegant) -->
<div class="bg-slate-900/95 dark:bg-slate-950 text-slate-300 text-xs py-2 px-4 shadow-xs border-b border-white/10 transition-colors">
    <div class="max-w-7xl mx-auto flex items-center justify-between gap-2">
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
        <div class="flex items-center gap-3 text-[11px] text-slate-400">
            <span id="auto-theme-badge" class="hidden sm:inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-white/10 text-slate-300 text-[10px] font-medium border border-white/10">
                <i class="fa-solid fa-circle-half-stroke text-amber-400"></i>
                <span id="auto-theme-label">Tema Otomatis</span>
            </span>
            <span class="text-slate-400 text-[11px] font-medium">Kota Medan (WIB)</span>
        </div>
    </div>
</div>

<script>
    (function() {
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

            // Update badge label
            const themeLabel = document.getElementById('auto-theme-label');
            if (themeLabel) {
                const h = now.getHours();
                themeLabel.textContent = (h >= 6 && h < 18) ? 'Mode Siang' : 'Mode Malam';
            }
        }
        
        updateLiveClockWidget();
        setInterval(updateLiveClockWidget, 1000);
    })();
</script>
