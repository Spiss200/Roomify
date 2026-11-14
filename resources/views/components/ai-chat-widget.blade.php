<!-- Roomify AI Chatbot Concierge Widget -->
<div id="roomify-chat-container" class="fixed bottom-5 right-5 z-50 font-sans" style="font-family: 'Inter', sans-serif;">

    <!-- Chat Trigger Floating Button -->
    <button id="chat-open-btn"
            type="button"
            onclick="toggleChatbot()"
            class="group relative flex items-center gap-3 px-4 py-3 rounded-full shadow-2xl text-white transition-all duration-300 transform hover:scale-105 active:scale-95"
            style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #ec4899 100%); box-shadow: 0 10px 30px -5px rgba(79, 70, 229, 0.5);"
            aria-label="Buka Roomify AI Concierge">
        <div class="relative w-7 h-7 flex items-center justify-center">
            <i class="fa-solid fa-robot text-lg"></i>
            <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-emerald-400 border-2 border-white rounded-full animate-ping"></span>
            <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-emerald-400 border-2 border-white rounded-full"></span>
        </div>
        <div class="text-left hidden sm:block pr-1">
            <span class="text-xs font-black tracking-wide block leading-tight">AI Concierge</span>
            <span class="text-[10px] text-indigo-100 font-medium block leading-tight">Cek Booking & Hotel</span>
        </div>
    </button>

    <!-- Chat Window Modal -->
    <div id="chat-window"
         class="hidden fixed bottom-5 right-5 w-[92vw] sm:w-[410px] h-[600px] max-h-[85vh] rounded-3xl shadow-2xl flex flex-col overflow-hidden border transition-all duration-300 bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800"
         style="box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.35);">

        <!-- Header -->
        <div class="px-5 py-4 flex items-center justify-between text-white shrink-0"
             style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 60%, #ec4899 100%);">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white border border-white/30 text-lg shadow-inner">
                    <i class="fa-solid fa-robot"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-sm leading-tight flex items-center gap-1.5">
                        Roomify AI Concierge
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    </h3>
                    <p class="text-[11px] text-indigo-100 font-medium">Asisten Hotel & Pesanan Real-Time</p>
                </div>
            </div>
            <button type="button"
                    onclick="toggleChatbot()"
                    class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition"
                    title="Tutup Chat">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Chat Messages Stream -->
        <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4 custom-scrollbar text-xs bg-slate-50/50 dark:bg-slate-900/50">
            <!-- Bot Welcome Bubble -->
            <div class="flex items-start gap-2.5">
                <div class="w-7 h-7 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-xs shrink-0 mt-0.5 shadow-sm">
                    <i class="fa-solid fa-robot"></i>
                </div>
                <div class="max-w-[85%] bg-white dark:bg-slate-800 p-3.5 rounded-2xl rounded-tl-sm border border-slate-200/80 dark:border-slate-700/80 shadow-sm text-slate-800 dark:text-slate-200 leading-relaxed">
                    <p class="font-bold text-indigo-600 dark:text-indigo-400 mb-1">Halo! Saya Roomify AI Concierge 🛎️</p>
                    <p>Saya terhubung langsung dengan sistem reservasi Roomify. Anda dapat mengecek status tiket booking Anda, menanyakan rekomendasi hotel, rute Google Maps, atau aturan check-in (14:00) dan check-out (12:00).</p>
                </div>
            </div>

            <!-- Quick Suggestion Chips -->
            <div id="chat-quick-chips" class="pt-1 flex flex-wrap gap-1.5">
                <button type="button" onclick="sendQuickPrompt('cek pesanan saya')" class="px-2.5 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 dark:bg-slate-800 dark:hover:bg-slate-700 text-indigo-700 dark:text-indigo-300 font-semibold text-[11px] border border-indigo-200/60 dark:border-slate-700 transition">
                    📋 Cek Pesanan Saya
                </button>
                <button type="button" onclick="sendQuickPrompt('hotel yang cambridge itu')" class="px-2.5 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 dark:bg-slate-800 dark:hover:bg-slate-700 text-indigo-700 dark:text-indigo-300 font-semibold text-[11px] border border-indigo-200/60 dark:border-slate-700 transition">
                    🏨 Hotel Cambridge
                </button>
                <button type="button" onclick="sendQuickPrompt('hotel di jalan h.m yamin')" class="px-2.5 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 dark:bg-slate-800 dark:hover:bg-slate-700 text-indigo-700 dark:text-indigo-300 font-semibold text-[11px] border border-indigo-200/60 dark:border-slate-700 transition">
                    📍 Hotel di Jl. H.M. Yamin
                </button>
                <button type="button" onclick="sendQuickPrompt('jam check in dan syarat ktp')" class="px-2.5 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 dark:bg-slate-800 dark:hover:bg-slate-700 text-indigo-700 dark:text-indigo-300 font-semibold text-[11px] border border-indigo-200/60 dark:border-slate-700 transition">
                    ⏰ Waktu Check-In & KTP
                </button>
                <button type="button" onclick="sendQuickPrompt('hotel dekat Stasiun Medan')" class="px-2.5 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 dark:bg-slate-800 dark:hover:bg-slate-700 text-indigo-700 dark:text-indigo-300 font-semibold text-[11px] border border-indigo-200/60 dark:border-slate-700 transition">
                    🚆 Dekat Stasiun Medan
                </button>
            </div>
        </div>

        <!-- Typing Indicator -->
        <div id="chat-typing" class="hidden px-5 py-2 text-slate-400 dark:text-slate-500 text-[11px] flex items-center gap-2 bg-slate-50 dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-bounce"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-bounce" style="animation-delay: 0.2s"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-bounce" style="animation-delay: 0.4s"></span>
            </div>
            <span>Roomify AI sedang mencari data...</span>
        </div>

        <!-- Chat Input Form -->
        <div class="p-3 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 shrink-0">
            <form id="chat-form" onsubmit="handleChatSubmit(event)" class="flex items-center gap-2">
                <input type="text"
                       id="chat-input"
                       placeholder="Tanya kode booking (RMF-...) atau nama hotel..."
                       autocomplete="off"
                       class="flex-1 bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                <button type="submit"
                        id="chat-send-btn"
                        class="px-3.5 py-2.5 rounded-xl text-white font-bold transition shadow flex items-center justify-center shrink-0"
                        style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                </button>
            </form>
        </div>

    </div>
</div>

<script>
    let chatbotOpen = false;

    function toggleChatbot() {
        const win = document.getElementById('chat-window');
        const btn = document.getElementById('chat-open-btn');
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
    }

    function sendQuickPrompt(text) {
        const input = document.getElementById('chat-input');
        if (input) {
            input.value = text;
            handleChatSubmit(new Event('submit'));
        }
    }

    async function handleChatSubmit(e) {
        if (e && e.preventDefault) e.preventDefault();
        const input = document.getElementById('chat-input');
        const text = input.value.trim();
        if (!text) return;

        input.value = '';

        // Append User Message
        appendUserMessage(text);

        // Show typing
        const typing = document.getElementById('chat-typing');
        const messages = document.getElementById('chat-messages');
        typing.classList.remove('hidden');
        messages.scrollTop = messages.scrollHeight;

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const res = await fetch('/api/ai-chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ message: text })
            });

            const data = await res.json();
            typing.classList.add('hidden');
            appendBotReply(data.reply, data.action, data.suggestions || []);
        } catch (err) {
            typing.classList.add('hidden');
            appendBotReply('Maaf, terjadi gangguan saat menghubungkan ke database/AI. Silakan coba kembali sesaat lagi.', null, ['Cek pesanan saya', 'Waktu check-in']);
        }
    }

    function appendUserMessage(text) {
        const container = document.getElementById('chat-messages');
        const div = document.createElement('div');
        div.className = 'flex items-start justify-end gap-2.5';
        div.innerHTML = `
            <div class="max-w-[85%] p-3 rounded-2xl rounded-tr-sm text-white font-medium shadow-sm leading-relaxed"
                 style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
                ${escapeHtml(text)}
            </div>
        `;
        container.appendChild(div);
        container.scrollTop = container.scrollHeight;
    }

    function appendBotReply(replyText, action, suggestions) {
        const container = document.getElementById('chat-messages');
        const div = document.createElement('div');
        div.className = 'flex items-start gap-2.5';

        // Format markdown bold, italic, code
        let formattedText = replyText
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.*?)\*/g, '<em>$1</em>')
            .replace(/`([^`]+)`/g, '<code class="bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 px-1.5 py-0.5 rounded text-[11px] font-mono border border-indigo-200/50 dark:border-indigo-800">$1</code>')
            .replace(/\n/g, '<br>');

        let actionHtml = '';
        if (action && action.label && action.url) {
            actionHtml = `
                <div class="mt-3 pt-2.5 border-t border-slate-100 dark:border-slate-700">
                    <a href="${action.url}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm transition">
                        <span>${escapeHtml(action.label)}</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            `;
        }

        let suggestionsHtml = '';
        if (suggestions && suggestions.length > 0) {
            suggestionsHtml = '<div class="mt-3 flex flex-wrap gap-1.5">';
            suggestions.forEach(s => {
                suggestionsHtml += `
                    <button type="button" onclick="sendQuickPrompt('${escapeHtml(s)}')" class="px-2 py-1 rounded-lg bg-slate-100 hover:bg-indigo-50 dark:bg-slate-700/60 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-[10px] font-medium border border-slate-200 dark:border-slate-600 transition">
                        ${escapeHtml(s)}
                    </button>
                `;
            });
            suggestionsHtml += '</div>';
        }

        div.innerHTML = `
            <div class="w-7 h-7 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-xs shrink-0 mt-0.5 shadow-sm">
                <i class="fa-solid fa-robot"></i>
            </div>
            <div class="max-w-[88%] bg-white dark:bg-slate-800 p-3.5 rounded-2xl rounded-tl-sm border border-slate-200/80 dark:border-slate-700/80 shadow-sm text-slate-800 dark:text-slate-200 leading-relaxed">
                <div>${formattedText}</div>
                ${actionHtml}
                ${suggestionsHtml}
            </div>
        `;

        container.appendChild(div);
        container.scrollTop = container.scrollHeight;
    }

    function escapeHtml(string) {
        const entityMap = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;',
            '/': '&#x2F;'
        };
        return String(string).replace(/[&<>"'\/]/g, s => entityMap[s]);
    }
</script>
