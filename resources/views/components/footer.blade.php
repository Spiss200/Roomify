<!-- Unified High-End Footer Component -->
<footer class="bg-slate-950 text-slate-400 border-t border-slate-800/80 pt-16 pb-10 px-4 sm:px-6 lg:px-8 mt-auto select-none">
    <div class="max-w-7xl mx-auto">

        <!-- Top Grid: 4 Columns -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">

            <!-- Col 1: Brand & Ontology Context -->
            <div class="space-y-4">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group no-underline">
                    <x-application-logo class="w-10 h-10 drop-shadow transition transform group-hover:scale-105" />
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xl font-black text-white tracking-tight group-hover:text-blue-400 transition">Roomify</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-blue-300 bg-blue-950/80 border border-blue-800/80 px-2 py-0.5 rounded-full">Medan</span>
                        </div>
                        <p class="text-[10px] text-slate-400 font-medium">Smart Semantic Hotel & Tourism Navigator</p>
                    </div>
                </a>

                <p class="text-xs text-slate-400 leading-relaxed">
                    Platform pencarian dan pemesanan <strong class="text-slate-200">312 hotel</strong> di Kota Medan terhubung dengan <strong class="text-slate-200">24 landmark ikonik</strong>. Berbasis ontologi Web Semantik RDF/OWL dengan dukungan triplestore Apache Jena Fuseki.
                </p>

                <!-- System Operational Status Pill -->
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-emerald-950/50 border border-emerald-800/60 text-emerald-300 text-[11px] font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Semua Sistem Operasional Normal</span>
                </div>
            </div>

            <!-- Col 2: Landmark Populer Medan -->
            <div>
                <h4 class="text-white text-xs font-black uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-landmark text-amber-400 text-xs"></i>
                    <span>Landmark Populer</span>
                </h4>
                <ul class="space-y-2.5 text-xs">
                    <li>
                        <a href="{{ url('/search?landmark=Istana+Maimun') }}" class="hover:text-white transition flex items-center gap-2 text-slate-400">
                            <i class="fa-solid fa-chevron-right text-[9px] text-slate-400"></i> Istana Maimun
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/search?landmark=Tjong+A+Fie+Mansion') }}" class="hover:text-white transition flex items-center gap-2 text-slate-400">
                            <i class="fa-solid fa-chevron-right text-[9px] text-slate-400"></i> Tjong A Fie Mansion
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/search?landmark=Masjid+Raya+Al+Mashun') }}" class="hover:text-white transition flex items-center gap-2 text-slate-400">
                            <i class="fa-solid fa-chevron-right text-[9px] text-slate-400"></i> Masjid Raya Al-Mashun
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/search?landmark=Lapangan+Merdeka') }}" class="hover:text-white transition flex items-center gap-2 text-slate-400">
                            <i class="fa-solid fa-chevron-right text-[9px] text-slate-400"></i> Lapangan Merdeka (KM 0)
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/search?landmark=Sun+Plaza') }}" class="hover:text-white transition flex items-center gap-2 text-slate-400">
                            <i class="fa-solid fa-chevron-right text-[9px] text-slate-400"></i> Sun Plaza Medan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('landmark.index') }}" class="text-blue-400 hover:text-blue-300 font-bold transition flex items-center gap-1.5 pt-1">
                            <span>Lihat Seluruh 24 Landmark</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Col 3: Navigasi Platform -->
            <div>
                <h4 class="text-white text-xs font-black uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-compass text-blue-400 text-xs"></i>
                    <span>Navigasi Platform</span>
                </h4>
                <ul class="space-y-2.5 text-xs">
                    <li>
                        <a href="{{ route('home') }}" class="hover:text-white transition flex items-center gap-2 text-slate-400">
                            <i class="fa-solid fa-house text-[10px] text-slate-400"></i> Beranda Utama
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('search') }}" class="hover:text-white transition flex items-center gap-2 text-slate-400">
                            <i class="fa-solid fa-magnifying-glass text-[10px] text-slate-400"></i> Cari & Filter 312 Hotel
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('landmark.index') }}" class="hover:text-white transition flex items-center gap-2 text-slate-400">
                            <i class="fa-solid fa-map-location-dot text-[10px] text-slate-400"></i> Direktori 24 Landmark
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('semantik.index') }}" class="hover:text-white transition flex items-center gap-2 text-slate-400">
                            <i class="fa-solid fa-diagram-project text-[10px] text-slate-400"></i> SPARQL Playground & Ontologi
                        </a>
                    </li>
                    @auth
                        <li>
                            <a href="{{ route('dashboard') }}" class="hover:text-white transition flex items-center gap-2 text-slate-400">
                                <i class="fa-solid fa-circle-user text-[10px] text-slate-400"></i> Dashboard Pengguna
                            </a>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('login') }}" class="hover:text-white transition flex items-center gap-2 text-slate-400">
                                <i class="fa-solid fa-right-to-bracket text-[10px] text-slate-400"></i> Masuk Akun
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('register') }}" class="hover:text-white transition flex items-center gap-2 text-slate-400">
                                <i class="fa-solid fa-user-plus text-[10px] text-slate-400"></i> Daftar Akun Baru
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>

            <!-- Col 4: Layanan & Ketentuan Reservasi -->
            <div>
                <h4 class="text-white text-xs font-black uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-clipboard-check text-emerald-400 text-xs"></i>
                    <span>Ketentuan & Standar</span>
                </h4>
                <ul class="space-y-2.5 text-xs text-slate-400">
                    <li class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span>
                        <span>Waktu Check-in: <strong>14:00 WIB</strong></span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400 shrink-0"></span>
                        <span>Waktu Check-out: <strong>12:00 WIB</strong></span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400 shrink-0"></span>
                        <span>Verifikasi Identitas: <strong>e-KTP 16 Digit</strong></span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 shrink-0"></span>
                        <span>Navigasi Peta: <strong>Google Maps + OSRM</strong></span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 shrink-0"></span>
                        <span>Concierge Pintar: <strong>AI Chatbot 24/7</strong></span>
                    </li>
                </ul>

                <!-- Triplestore Specs Badge -->
                <div class="mt-4 p-3 rounded-xl bg-slate-900 border border-slate-800 text-[10px] text-slate-400 space-y-1">
                    <div class="flex items-center justify-between">
                        <span>Basis Pengetahuan:</span>
                        <span class="font-bold text-slate-200">12.483 Triples</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>SPARQL Triplestore:</span>
                        <span class="font-bold text-indigo-400">Apache Jena Fuseki</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Bottom Copyright Bar -->
        <div class="pt-8 border-t border-slate-800/80 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400 gap-4">
            <p>&copy; {{ date('Y') }} Roomify Medan. Seluruh hak cipta dilindungi undang-undang.</p>
            <div class="flex items-center gap-4 text-xs">
                <span>Tugas Besar Web Semantik • Kota Medan</span>
                <span>•</span>
                <a href="{{ route('semantik.index') }}" class="text-blue-400 hover:text-blue-300 font-semibold transition">Ontologi RDF/OWL</a>
            </div>
        </div>

    </div>
</footer>
