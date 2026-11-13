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
            <span class="text-[10px] text-indigo-100 font-medium block leading-tight">Tanya Hotel & Rute</span>
        </div>
    </button>

    <!-- Chat Window Modal -->
    <div id="chat-window"
         class="hidden fixed bottom-5 right-5 w-[92vw] sm:w-[400px] h-[580px] max-h-[85vh] rounded-3xl shadow-2xl flex flex-col overflow-hidden border transition-all duration-300 bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800"
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
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    </h3>
                    <p class="text-[11px] text-indigo-100 font-medium">Asisten Cerdas Hotel & Landmark Medan</p>
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
                    <p class="font-bold text-indigo-600 dark:text-indigo-400 mb-1">Halo! Selamat datang di Roomify.</p>
                    <p>Saya asisten Roomify untuk 187 hotel di Medan. Anda bisa bertanya rekomendasi hotel terdekat, hotel di jalan tertentu, jadwal check-in, atau syarat booking.</p>
                </div>
            </div>

            <!-- Quick Suggestion Chips -->
            <div id="chat-quick-chips" class="pt-1 flex flex-wrap gap-1.5">
                <button type="button" onclick="sendQuickPrompt('hotel yang cambridge itu')" class="px-2.5 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 dark:bg-slate-800 dark:hover:bg-slate-700 text-indigo-700 dark:text-indigo-300 font-semibold text-[11px] border border-indigo-200/60 dark:border-slate-700 transition">
                    🏨 Hotel yang Cambridge itu
                </button>
                <button type="button" onclick="sendQuickPrompt('hotel di jalan h.m yamin')" class="px-2.5 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 dark:bg-slate-800 dark:hover:bg-slate-700 text-indigo-700 dark:text-indigo-300 font-semibold text-[11px] border border-indigo-200/60 dark:border-slate-700 transition">
                    📍 Hotel di Jalan H.M. Yamin
                </button>
                <button type="button" onclick="sendQuickPrompt('hotel dekat Istana Maimun')" class="px-2.5 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 dark:bg-slate-800 dark:hover:bg-slate-700 text-indigo-700 dark:text-indigo-300 font-semibold text-[11px] border border-indigo-200/60 dark:border-slate-700 transition">
                    🏛️ Dekat Istana Maimun
                </button>
                <button type="button" onclick="sendQuickPrompt('hotel bintang 5 mewah di Medan')" class="px-2.5 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 dark:bg-slate-800 dark:hover:bg-slate-700 text-indigo-700 dark:text-indigo-300 font-semibold text-[11px] border border-indigo-200/60 dark:border-slate-700 transition">
                    ⭐ Hotel Bintang 5 Mewah
                </button>
                <button type="button" onclick="sendQuickPrompt('check in jam berapa dan syaratnya apa')" class="px-2.5 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 dark:bg-slate-800 dark:hover:bg-slate-700 text-indigo-700 dark:text-indigo-300 font-semibold text-[11px] border border-indigo-200/60 dark:border-slate-700 transition">
                    ⏰ Jadwal Check-in & KTP
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
            <span>Roomify AI sedang menganalisis data hotel...</span>
        </div>

        <!-- Chat Input Form -->
        <div class="p-3 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 shrink-0">
            <form id="chat-form" onsubmit="handleChatSubmit(event)" class="flex items-center gap-2">
                <input type="text"
                       id="chat-input"
                       placeholder="Ketik pertanyaan (cth: hotel di jl hm yamin)..."
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
            appendBotReply(data.reply, data.hotels || []);
        } catch (err) {
            typing.classList.add('hidden');
            appendBotReply('Maaf, terjadi gangguan saat menghubungkan ke server AI. Silakan coba kembali sesaat lagi.', []);
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

    function appendBotReply(replyText, hotels) {
        const container = document.getElementById('chat-messages');
        const div = document.createElement('div');
        div.className = 'flex items-start gap-2.5';

        // Format simple markdown bold and bullet points
        let formattedText = replyText
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.*?)\*/g, '<em>$1</em>')
            .replace(/\n/g, '<br>');

        let hotelsHtml = '';
        if (hotels && hotels.length > 0) {
            hotelsHtml = '<div class="mt-3 space-y-2">';
            hotels.forEach(h => {
                hotelsHtml += `
                    <div class="p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/80 flex items-center gap-3">
                        <img src="${h.foto}" alt="${h.nama}" class="w-14 h-14 rounded-lg object-cover shrink-0 border border-slate-200 dark:border-slate-700" onerror="this.src='/images/placeholder.svg'">
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-slate-900 dark:text-white text-xs truncate">${h.nama}</h4>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate">${h.alamat}</p>
                            <div class="flex items-center justify-between mt-1">
                                <span class="font-black text-indigo-600 dark:text-indigo-400 text-xs">${h.harga}</span>
                                <a href="${h.detail_url}" class="text-[10px] font-bold px-2 py-0.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition">
                                    Detail →
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            });
            hotelsHtml += '</div>';
        }

        div.innerHTML = `
            <div class="w-7 h-7 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-xs shrink-0 mt-0.5 shadow-sm">
                <i class="fa-solid fa-robot"></i>
            </div>
            <div class="max-w-[85%] bg-white dark:bg-slate-800 p-3.5 rounded-2xl rounded-tl-sm border border-slate-200/80 dark:border-slate-700/80 shadow-sm text-slate-800 dark:text-slate-200 leading-relaxed">
                <div>${formattedText}</div>
                ${hotelsHtml}
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
