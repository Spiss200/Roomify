@props(['size' => 'md', 'dark' => false])

@php
    $iconSize = match($size) {
        'sm' => 'w-8 h-8',
        'lg' => 'w-12 h-12',
        default => 'w-10 h-10',
    };
    $textSize = match($size) {
        'sm' => 'text-lg',
        'lg' => 'text-2xl',
        default => 'text-xl',
    };
    $badgeText = match($size) {
        'sm' => 'text-[10px] px-1.5 py-0.2',
        'lg' => 'text-xs px-2.5 py-0.5',
        default => 'text-[11px] px-2 py-0.5',
    };
@endphp

<div class="flex items-center gap-2.5 group">
    <div class="{{ $iconSize }} relative transform transition-transform duration-300 group-hover:scale-105">
        <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full drop-shadow-md">
            <defs>
                <linearGradient id="rmfy-bg-grad-comp" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#4F46E5"/>
                    <stop offset="50%" stop-color="#7C3AED"/>
                    <stop offset="100%" stop-color="#EC4899"/>
                </linearGradient>
                <linearGradient id="rmfy-gold-grad-comp" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#FCD34D"/>
                    <stop offset="100%" stop-color="#F59E0B"/>
                </linearGradient>
            </defs>
            <rect width="48" height="48" rx="14" fill="url(#rmfy-bg-grad-comp)"/>
            <rect x="0.75" y="0.75" width="46.5" height="46.5" rx="13.25" stroke="rgba(255,255,255,0.35)" stroke-width="1.5"/>
            <path d="M12 36V22C12 15.3726 17.3726 10 24 10C30.6274 10 36 15.3726 36 22V36" stroke="#FFFFFF" stroke-width="3" stroke-linecap="round"/>
            <path d="M18 36V18H25.5C28 18 29.5 19.5 29.5 22C29.5 24.5 28 26 25.5 26H18M24 26L30 36" stroke="#FFFFFF" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M34 11L35.2 13.8L38 15L35.2 16.2L34 19L32.8 16.2L30 15L32.8 13.8L34 11Z" fill="url(#rmfy-gold-grad-comp)"/>
            <circle cx="24" cy="31" r="1.5" fill="#FCD34D"/>
        </svg>
    </div>
    <div class="flex flex-col">
        <div class="flex items-center gap-1.5 leading-none">
            <span class="{{ $textSize }} font-black tracking-tight bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 bg-clip-text text-transparent">
                Roomify
            </span>
        </div>
        <span class="text-[9px] font-semibold text-slate-400 tracking-wider uppercase mt-0.5">Semantic Hotel Discovery</span>
    </div>
</div>
