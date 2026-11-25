<!-- Roomify AI Chatbot Concierge Widget (Powered by Gemini AI + Grounded Semantic Catalog) -->
<div id="roomify-chat-container" class="fixed bottom-4 right-4 sm:bottom-5 sm:right-5 z-50 font-sans" style="font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">

    <!-- Chat Trigger Floating Button -->
    <button id="chat-open-btn"
            type="button"
            onclick="toggleChatbot()"
            class="group relative flex items-center gap-3 px-4 py-3 rounded-full shadow-2xl text-white transition-all duration-300 transform hover:scale-105 active:scale-95 bg-slate-900 dark:bg-slate-800 border-2 border-slate-700/80 hover:border-indigo-500 ring-4 ring-indigo-500/10"
            aria-label="Buka Roomify AI Concierge">
        <div class="relative w-7 h-7 flex items-center justify-center text-indigo-400">
            <i class="fa-solid fa-sparkles text-lg"></i>
            <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-emerald-400 border-2 border-slate-900 rounded-full animate-ping"></span>
            <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-emerald-400 border-2 border-slate-900 rounded-full"></span>
        </div>
        <div class="text-left hidden sm:block pr-1">
            <span class="text-xs font-black tracking-wide flex items-center gap-1.5 leading-tight text-white">
                AI Concierge
                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full bg-indigo-500/30 text-indigo-200 border border-indigo-400/30">Gemini</span>
            </span>
            <span class="text-[10px] text-slate-300 font-medium block leading-tight">Asisten Hotel & Wisata Medan</span>
        </div>
    </button>

    <!-- Chat Window Modal -->
    <div id="chat-window"
         class="hidden fixed bottom-2 sm:bottom-5 right-2 sm:right-5 w-[calc(100vw-1rem)] sm:w-[420px] max-w-[calc(100vw-1rem)] sm:max-w-[calc(100vw-2.5rem)] h-[min(82vh,640px)] max-h-[calc(100vh-5.25rem)] rounded-3xl shadow-2xl flex flex-col overflow-hidden border-2 transition-all duration-300 bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800 z-50"
         style="box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.45);">

        <!-- Header -->
        <div class="px-4 py-3.5 flex items-center justify-between text-white shrink-0 bg-slate-900 dark:bg-slate-950 border-b border-slate-800">
            <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-9 h-9 rounded-2xl bg-gradient-to-br from-indigo-500/30 to-purple-500/30 text-indigo-300 flex items-center justify-center border border-indigo-400/30 text-base shrink-0">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="font-extrabold text-sm leading-tight flex items-center gap-1.5 text-white truncate">
                        Roomify AI Concierge
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse shrink-0"></span>
                    </h3>
                    <p class="text-[10px] text-slate-400 font-medium flex items-center gap-1.5 truncate">
                        <span>Powered by Gemini AI</span>
                        <span class="text-slate-600">•</span>
                        <span>312 Hotel Medan</span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-1.5 shrink-0">
                <button type="button"
                        id="chat-expand-btn"
                        onclick="toggleChatbotWidth()"
                        class="hidden sm:flex w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white items-center justify-center transition"
                        title="Perluas / Kecilkan Jendela Chat"
                        aria-label="Perluas Jendela Chat">
                    <i id="chat-expand-icon" class="fa-solid fa-up-right-and-down-left-from-center text-xs"></i>
                </button>
                <button type="button"
                        onclick="resetChatbotHistory()"
                        class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition"
                        title="Mulai Percakapan Baru"
                        aria-label="Mulai Percakapan Baru">
                    <i class="fa-solid fa-rotate-right text-xs"></i>
                </button>
                <button type="button"
                        onclick="toggleChatbot()"
                        class="w-8 h-8 rounded-xl bg-white/10 hover:bg-rose-500/80 text-slate-300 hover:text-white flex items-center justify-center transition"
                        title="Tutup Chat"
                        aria-label="Tutup Chat">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
        </div>

        <!-- Chat Messages Stream -->
        <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4 custom-scrollbar text-xs bg-slate-50/70 dark:bg-slate-900/60"></div>

        <!-- Typing Indicator -->
        <div id="chat-typing" class="hidden px-4 py-2 text-slate-500 dark:text-slate-400 text-[11px] flex items-center gap-2 bg-slate-50 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 shrink-0">
            <div class="flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-bounce"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-bounce" style="animation-delay: 0.2s"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-bounce" style="animation-delay: 0.4s"></span>
            </div>
            <span>Gemini AI sedang menganalisis data hotel...</span>
        </div>

        <!-- Chat Input Form -->
        <div class="p-3 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 shrink-0 space-y-2">
            <div class="flex items-center justify-between gap-2 px-0.5">
                <button type="button"
                        id="chat-gps-btn"
                        onclick="askNearbyHotelsWithGps()"
                        class="text-[10px] font-semibold px-2.5 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/50 dark:hover:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/70 flex items-center gap-1 transition">
                    <i class="fa-solid fa-location-crosshairs"></i>
                    <span>Hotel Dekat Saya (GPS)</span>
                </button>
                <span class="text-[10px] text-slate-400 dark:text-slate-500 hidden sm:inline">Mendukung perbandingan, budget & itinerary</span>
            </div>
            <form id="chat-form" onsubmit="handleChatSubmit(event)" class="flex items-center gap-2">
                <input type="text"
                       id="chat-input"
                       maxlength="1500"
                       placeholder="Tanya hotel dekat USU < 500rb, perbandingan, atau RMF-..."
                       autocomplete="off"
                       class="flex-1 bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-slate-100 placeholder-slate-400 text-xs px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                <button type="submit"
                        id="chat-send-btn"
                        class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold transition shadow-xs flex items-center justify-center shrink-0"
                        aria-label="Kirim Pesan">
                    <i id="chat-send-icon" class="fa-solid fa-paper-plane text-xs"></i>
                </button>
            </form>
        </div>

    </div>
</div>

<script>
    (function () {
        const CHAT_STORAGE_KEY = 'roomify_ai_chat_history_v2';
        let chatbotOpen = false;
        let chatbotExpanded = false;
        let isSendingChat = false;
        let userGpsCoords = null;
        let chatTranscript = [];

        function getPageContext() {
            const path = window.location.pathname || '/';
            const hotelMatch = path.match(/^\/hotel\/([^\/?#]+)/i);
            const landmarkMatch = path.match(/^\/landmark\/([^\/?#]+)/i);

            const ctx = {
                page_path: path,
                hotel_id: hotelMatch ? decodeURIComponent(hotelMatch[1]) : null,
                landmark_id: landmarkMatch ? decodeURIComponent(landmarkMatch[1]) : null
            };

            if (userGpsCoords) {
                ctx.user_lat = userGpsCoords.lat;
                ctx.user_lng = userGpsCoords.lng;
            }

            return ctx;
        }

        function escapeHtml(value) {
            const entityMap = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;',
                '/': '&#x2F;'
            };
            return String(value ?? '').replace(/[&<>"'\/]/g, s => entityMap[s]);
        }

        function safeUrl(rawUrl) {
            if (!rawUrl) return '#';
            const str = String(rawUrl).trim();
            if (str.startsWith('/') || str.startsWith('https://') || str.startsWith('http://')) {
                return str;
            }
            return '#';
        }

        function formatMarkdownSafe(rawText) {
            const escaped = escapeHtml(rawText);
            return escaped
                .replace(/^###\s+(.+)$/gm, '<div class="font-extrabold text-slate-900 dark:text-white mt-1.5 mb-0.5">$1</div>')
                .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
                .replace(/\*(.+?)\*/g, '<em>$1</em>')
                .replace(/`([^`]+)`/g, '<code class="bg-slate-100 dark:bg-slate-800 text-indigo-600 dark:text-indigo-300 px-1.5 py-0.5 rounded text-[11px] font-mono border border-slate-200 dark:border-slate-700">$1</code>')
                .replace(/\n/g, '<br>');
        }

        function loadSavedTranscript() {
            try {
                const raw = sessionStorage.getItem(CHAT_STORAGE_KEY);
                if (!raw) return [];
                const parsed = JSON.parse(raw);
                return Array.isArray(parsed) ? parsed.slice(-16) : [];
            } catch (e) {
                return [];
            }
        }

        function saveTranscript() {
            try {
                sessionStorage.setItem(CHAT_STORAGE_KEY, JSON.stringify(chatTranscript.slice(-16)));
            } catch (e) {
                // Ignore storage quota errors
            }
        }

        function getWelcomeSuggestions() {
            const ctx = getPageContext();
            if (ctx.hotel_id) {
                return [
                    'Info fasilitas & harga hotel ini',
                    'Hotel lain dekat sini',
                    'Ketentuan check-in & e-KTP',
                    'Cek pesanan saya'
                ];
            }
            if (ctx.landmark_id) {
                return [
                    'Hotel terdekat dari landmark ini',
                    'Hotel murah dekat sini',
                    'Ketentuan check-in',
                    'Cek pesanan saya'
                ];
            }
            return [
                'Hotel dekat USU dibawah 500rb',
                'Hotel bintang 5 terbaik di Medan',
                'Bandingkan JW Marriott dan Cambridge',
                'Cek pesanan saya'
            ];
        }

        function renderWelcomeState() {
            const container = document.getElementById('chat-messages');
            if (!container) return;
            container.innerHTML = '';

            const welcomeDiv = document.createElement('div');
            welcomeDiv.className = 'flex items-start gap-2.5';
            welcomeDiv.innerHTML = `
                <div class="w-7 h-7 rounded-xl bg-slate-800 text-indigo-400 border border-slate-700 flex items-center justify-center text-xs shrink-0 mt-0.5">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                </div>
                <div class="max-w-[88%] bg-white dark:bg-slate-800 p-3.5 rounded-2xl rounded-tl-xs border border-slate-200/90 dark:border-slate-700 shadow-xs text-slate-800 dark:text-slate-200 leading-relaxed">
                    <p class="font-bold text-slate-900 dark:text-white mb-1">Halo! Saya Roomify AI Concierge 🛎️</p>
                    <p>Sekarang didukung <strong>Gemini AI</strong> yang terhubung langsung ke 312 data hotel &amp; 24 landmark Medan. Tanyakan rekomendasi sesuai budget, fasilitas, perbandingan hotel, itinerary, atau cek tiket <code class="bg-slate-100 dark:bg-slate-900 text-indigo-600 dark:text-indigo-300 px-1 rounded">RMF-...</code> Anda.</p>
                </div>
            `;
            container.appendChild(welcomeDiv);

            const chipsWrap = document.createElement('div');
            chipsWrap.id = 'chat-quick-chips';
            chipsWrap.className = 'pt-1 flex flex-wrap gap-1.5';

            getWelcomeSuggestions().forEach(label => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'chat-prompt-chip px-2.5 py-1.5 rounded-xl bg-white dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-semibold text-[11px] border border-slate-200 dark:border-slate-700 shadow-xs transition text-left';
                btn.dataset.prompt = label;
                btn.textContent = label;
                chipsWrap.appendChild(btn);
            });

            container.appendChild(chipsWrap);
        }

        function appendUserBubble(text) {
            const container = document.getElementById('chat-messages');
            if (!container) return;

            const div = document.createElement('div');
            div.className = 'flex items-start justify-end gap-2.5';
            div.innerHTML = `
                <div class="max-w-[85%] p-3 rounded-2xl rounded-tr-xs bg-slate-900 text-white dark:bg-indigo-600 font-medium shadow-xs leading-relaxed break-words">
                    ${escapeHtml(text)}
                </div>
            `;
            container.appendChild(div);
            container.scrollTop = container.scrollHeight;
        }

        function appendBotBubble(payload) {
            const container = document.getElementById('chat-messages');
            if (!container) return;

            const replyText = payload.reply || '';
            const action = payload.action || null;
            const suggestions = Array.isArray(payload.suggestions) ? payload.suggestions : [];
            const hotels = Array.isArray(payload.hotels) ? payload.hotels : [];
            const location = payload.location || null;
            const engine = payload.engine || null;

            const div = document.createElement('div');
            div.className = 'flex items-start gap-2.5';

            let locationHtml = '';
            if (location && location.name) {
                const latText = typeof location.lat === 'number' ? location.lat.toFixed(4) : escapeHtml(location.lat || '');
                const lngText = typeof location.lng === 'number' ? location.lng.toFixed(4) : escapeHtml(location.lng || '');
                locationHtml = `
                    <div class="mt-3 p-3 rounded-2xl border-2 border-indigo-200 dark:border-indigo-800/80 bg-indigo-50/70 dark:bg-indigo-950/40">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[10px] font-black uppercase tracking-wider text-indigo-700 dark:text-indigo-300 flex items-center gap-1">
                                <i class="fa-solid fa-location-dot text-rose-500"></i> Lokasi &amp; Peta
                            </span>
                            <span class="text-[9px] font-mono text-slate-500 dark:text-slate-400 font-bold">${latText}, ${lngText}</span>
                        </div>
                        <h4 class="font-extrabold text-slate-900 dark:text-white text-xs">${escapeHtml(location.name)}</h4>
                        <p class="text-[11px] text-slate-600 dark:text-slate-400 mt-0.5 leading-snug">${escapeHtml(location.desc || '')}</p>
                        <div class="mt-2.5 flex items-center gap-2 flex-wrap">
                            <a href="${escapeHtml(safeUrl(location.directions_url))}" target="_blank" rel="noopener noreferrer" class="px-2.5 py-1 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[10px] flex items-center gap-1 shadow-xs transition">
                                <i class="fa-solid fa-location-arrow"></i>
                                <span>Buka Google Maps</span>
                            </a>
                            <a href="${escapeHtml(safeUrl(location.search_url))}" class="px-2.5 py-1 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-[10px] flex items-center gap-1 shadow-xs transition">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <span>Cari Hotel Sekitar</span>
                            </a>
                        </div>
                    </div>
                `;
            }

            let hotelsHtml = '';
            if (hotels.length > 0) {
                hotelsHtml = '<div class="mt-3 space-y-2">';
                hotels.forEach(h => {
                    const mapsLink = safeUrl(h.directions_url || h.maps_url || h.detail_url);
                    const detailLink = safeUrl(h.detail_url);
                    const imgSrc = safeUrl(h.foto || '/images/placeholder.svg');
                    const starBadge = h.star ? `<span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300">★ ${escapeHtml(h.star)}</span>` : '';

                    hotelsHtml += `
                        <div class="p-2.5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/80 flex flex-col gap-2">
                            <div class="flex items-center gap-2.5">
                                <img src="${escapeHtml(imgSrc)}" alt="${escapeHtml(h.nama)}" class="w-12 h-12 rounded-xl object-cover shrink-0 border border-slate-200 dark:border-slate-700" onerror="this.src='/images/placeholder.svg'">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-1.5">
                                        <h4 class="font-bold text-slate-900 dark:text-white text-xs truncate">${escapeHtml(h.nama)}</h4>
                                        ${starBadge}
                                    </div>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate">${escapeHtml(h.alamat)}</p>
                                    <div class="flex items-center justify-between gap-1 mt-0.5">
                                        <span class="font-black text-indigo-600 dark:text-indigo-400 text-xs">${escapeHtml(h.harga)}</span>
                                        <div class="flex items-center gap-1">
                                            ${h.rating ? `<span class="text-[10px] font-bold text-amber-600 dark:text-amber-400">⭐ ${escapeHtml(h.rating)}</span>` : ''}
                                            ${h.jarak ? `<span class="text-[10px] font-mono text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-50 dark:bg-emerald-950/70 px-1.5 py-0.5 rounded">📍 ${escapeHtml(h.jarak)}</span>` : ''}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between gap-1.5 pt-1.5 border-t border-slate-200/80 dark:border-slate-800">
                                <a href="${escapeHtml(mapsLink)}" target="_blank" rel="noopener noreferrer" class="text-[10px] font-bold px-2 py-1 rounded-xl bg-slate-200 hover:bg-slate-300 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 flex items-center gap-1 transition">
                                    <i class="fa-solid fa-map-location-dot text-indigo-500"></i> Rute Maps
                                </a>
                                <a href="${escapeHtml(detailLink)}" class="text-[10px] font-bold px-2.5 py-1 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white flex items-center gap-1 transition">
                                    <span>Detail Kamar</span> →
                                </a>
                            </div>
                        </div>
                    `;
                });
                hotelsHtml += '</div>';
            }

            let actionHtml = '';
            if (action && action.label && action.url) {
                actionHtml = `
                    <div class="mt-3 pt-2.5 border-t border-slate-200 dark:border-slate-700">
                        <a href="${escapeHtml(safeUrl(action.url))}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 dark:bg-indigo-600 dark:hover:bg-indigo-500 text-white font-bold text-xs shadow-xs transition">
                            <span>${escapeHtml(action.label)}</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                `;
            }

            const engineBadge = engine === 'gemini'
                ? '<div class="mt-2 flex items-center gap-1 text-[9px] font-semibold text-indigo-500 dark:text-indigo-400"><i class="fa-solid fa-sparkles"></i><span>Dijawab oleh Gemini AI</span></div>'
                : '';

            div.innerHTML = `
                <div class="w-7 h-7 rounded-xl bg-slate-800 text-indigo-400 border border-slate-700 flex items-center justify-center text-xs shrink-0 mt-0.5">
                    <i class="fa-solid fa-robot"></i>
                </div>
                <div class="max-w-[88%] bg-white dark:bg-slate-800 p-3.5 rounded-2xl rounded-tl-xs border border-slate-200/90 dark:border-slate-700 shadow-xs text-slate-800 dark:text-slate-200 leading-relaxed break-words">
                    <div>${formatMarkdownSafe(replyText)}</div>
                    ${locationHtml}
                    ${hotelsHtml}
                    ${actionHtml}
                    ${engineBadge}
                    <div class="chat-suggestions-slot"></div>
                </div>
            `;

            const slot = div.querySelector('.chat-suggestions-slot');
            if (slot && suggestions.length > 0) {
                const wrap = document.createElement('div');
                wrap.className = 'mt-3 flex flex-wrap gap-1.5';
                suggestions.forEach(s => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'chat-prompt-chip px-2 py-1 rounded-lg bg-slate-100 hover:bg-indigo-50 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 text-[10px] font-medium border border-slate-200 dark:border-slate-600 transition text-left';
                    btn.dataset.prompt = s;
                    btn.textContent = s;
                    wrap.appendChild(btn);
                });
                slot.appendChild(wrap);
            }

            container.appendChild(div);
            container.scrollTop = container.scrollHeight;
        }

        function setSendingState(sending) {
            isSendingChat = sending;
            const btn = document.getElementById('chat-send-btn');
            const icon = document.getElementById('chat-send-icon');
            const typing = document.getElementById('chat-typing');
            const messages = document.getElementById('chat-messages');

            if (btn) btn.disabled = sending;
            if (icon) {
                icon.className = sending
                    ? 'fa-solid fa-circle-notch fa-spin text-xs'
                    : 'fa-solid fa-paper-plane text-xs';
            }
            if (typing) {
                typing.classList.toggle('hidden', !sending);
            }
            if (sending && messages) {
                messages.scrollTop = messages.scrollHeight;
            }
        }

        window.toggleChatbot = function () {
            const win = document.getElementById('chat-window');
            const btn = document.getElementById('chat-open-btn');
            if (!win || !btn) return;

            chatbotOpen = !chatbotOpen;
            if (chatbotOpen) {
                win.classList.remove('hidden');
                btn.classList.add('hidden');
                setTimeout(() => {
                    document.getElementById('chat-input')?.focus();
                }, 100);
            } else {
                win.classList.add('hidden');
                btn.classList.remove('hidden');
            }
        };

        window.toggleChatbotWidth = function () {
            const win = document.getElementById('chat-window');
            const icon = document.getElementById('chat-expand-icon');
            if (!win) return;

            chatbotExpanded = !chatbotExpanded;
            if (chatbotExpanded) {
                win.classList.remove('sm:w-[420px]');
                win.classList.add('sm:w-[540px]');
                if (icon) icon.className = 'fa-solid fa-down-left-and-up-right-to-center text-xs';
            } else {
                win.classList.remove('sm:w-[540px]');
                win.classList.add('sm:w-[420px]');
                if (icon) icon.className = 'fa-solid fa-up-right-and-down-left-from-center text-xs';
            }
        };

        window.resetChatbotHistory = function () {
            chatTranscript = [];
            try {
                sessionStorage.removeItem(CHAT_STORAGE_KEY);
            } catch (e) {
                // Ignore
            }
            renderWelcomeState();
        };

        window.sendQuickPrompt = function (text) {
            const input = document.getElementById('chat-input');
            if (input && !isSendingChat) {
                input.value = text;
                window.handleChatSubmit(new Event('submit'));
            }
        };

        window.askNearbyHotelsWithGps = function () {
            if (isSendingChat) return;
            const gpsBtn = document.getElementById('chat-gps-btn');

            if (userGpsCoords) {
                window.sendQuickPrompt('Rekomendasi hotel terdekat dari lokasi saya');
                return;
            }

            if (!navigator.geolocation) {
                window.sendQuickPrompt('Rekomendasi hotel terdekat di pusat kota Medan');
                return;
            }

            if (gpsBtn) {
                gpsBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i><span>Mendeteksi GPS...</span>';
            }

            navigator.geolocation.getCurrentPosition(
                pos => {
                    userGpsCoords = {
                        lat: pos.coords.latitude,
                        lng: pos.coords.longitude
                    };
                    if (gpsBtn) {
                        gpsBtn.innerHTML = '<i class="fa-solid fa-location-dot"></i><span>GPS Aktif</span>';
                    }
                    window.sendQuickPrompt('Rekomendasi hotel terdekat dari lokasi saya');
                },
                () => {
                    if (gpsBtn) {
                        gpsBtn.innerHTML = '<i class="fa-solid fa-location-crosshairs"></i><span>Hotel Dekat Saya (GPS)</span>';
                    }
                    window.sendQuickPrompt('Rekomendasi hotel strategis di pusat kota Medan');
                },
                { timeout: 6000, maximumAge: 120000 }
            );
        };

        window.handleChatSubmit = async function (e) {
            if (e && e.preventDefault) e.preventDefault();
            if (isSendingChat) return;

            const input = document.getElementById('chat-input');
            const text = (input?.value || '').trim();
            if (!text) return;

            input.value = '';

            // Snapshot recent conversation history before appending current turn
            const historyPayload = chatTranscript
                .slice(-8)
                .map(item => ({
                    role: item.role === 'model' ? 'model' : 'user',
                    text: String(item.text || '').slice(0, 1200)
                }));

            appendUserBubble(text);
            chatTranscript.push({ role: 'user', text });
            saveTranscript();

            setSendingState(true);

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const res = await fetch('/api/ai-chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        message: text,
                        history: historyPayload,
                        context: getPageContext()
                    })
                });

                const data = await res.json();
                setSendingState(false);

                const botPayload = {
                    role: 'model',
                    text: data.reply || 'Maaf, saya belum menerima jawaban.',
                    reply: data.reply || 'Maaf, saya belum menerima jawaban.',
                    action: data.action || null,
                    suggestions: data.suggestions || [],
                    hotels: data.hotels || [],
                    location: data.location || null,
                    engine: data.engine || null
                };

                appendBotBubble(botPayload);
                chatTranscript.push(botPayload);
                saveTranscript();
            } catch (err) {
                setSendingState(false);
                const fallbackPayload = {
                    role: 'model',
                    text: 'Maaf, terjadi gangguan koneksi saat menghubungi layanan AI. Silakan coba kembali sesaat lagi.',
                    reply: 'Maaf, terjadi gangguan koneksi saat menghubungi layanan AI. Silakan coba kembali sesaat lagi.',
                    action: null,
                    suggestions: ['Cek pesanan saya', 'Waktu check-in', 'Hotel dekat USU'],
                    hotels: [],
                    location: null,
                    engine: 'local'
                };
                appendBotBubble(fallbackPayload);
            }
        };

        function initChatbotWidget() {
            const container = document.getElementById('chat-messages');
            if (!container) return;

            container.addEventListener('click', function (ev) {
                const chip = ev.target.closest('.chat-prompt-chip');
                if (chip && chip.dataset.prompt) {
                    window.sendQuickPrompt(chip.dataset.prompt);
                }
            });

            renderWelcomeState();
            const saved = loadSavedTranscript();
            if (saved.length > 0) {
                chatTranscript = saved;
                saved.forEach(item => {
                    if (item.role === 'user') {
                        appendUserBubble(item.text || '');
                    } else {
                        appendBotBubble(item);
                    }
                });
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initChatbotWidget);
        } else {
            initChatbotWidget();
        }
    })();
</script>
