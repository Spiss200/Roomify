@props(['size' => 'md', 'dark' => false])

@php
    $iconSize = match($size) {
        'sm' => 'w-7 h-7',
        'lg' => 'w-11 h-11',
        default => 'w-9 h-9',
    };
    $textSize = match($size) {
        'sm' => 'text-base',
        'lg' => 'text-2xl',
        default => 'text-xl',
    };
@endphp

<div class="flex items-center gap-2.5 group">
    <div class="{{ $iconSize }} relative transform transition-transform duration-300 group-hover:scale-105">
        <x-application-logo class="w-full h-full drop-shadow-xs" />
    </div>
    <div class="flex flex-col">
        <div class="flex items-center gap-1.5 leading-none">
            <span class="{{ $textSize }} font-extrabold tracking-tight text-slate-900 dark:text-white">
                Roomify
            </span>
            <span class="text-[9px] font-bold uppercase tracking-wider text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-950/70 border border-blue-200/80 dark:border-blue-800/80 px-1.5 py-0.2 rounded">
                Medan
            </span>
        </div>
        <span class="text-[9px] font-semibold text-slate-400 dark:text-slate-400 tracking-wider uppercase mt-0.5">Hotel & Wisata Semantik</span>
    </div>
</div>
