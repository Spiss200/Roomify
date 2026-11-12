<svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
    <defs>
        <linearGradient id="rmfy-bg-grad" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#4F46E5"/>
            <stop offset="50%" stop-color="#7C3AED"/>
            <stop offset="100%" stop-color="#EC4899"/>
        </linearGradient>
        <linearGradient id="rmfy-gold-grad" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#FCD34D"/>
            <stop offset="100%" stop-color="#F59E0B"/>
        </linearGradient>
        <linearGradient id="rmfy-roof-grad" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#FFFFFF"/>
            <stop offset="100%" stop-color="#E0E7FF"/>
        </linearGradient>
        <filter id="rmfy-shadow" x="-10%" y="-10%" width="130%" height="130%">
            <feDropShadow dx="0" dy="4" stdDeviation="3" flood-color="rgba(79, 70, 229, 0.4)"/>
        </filter>
    </defs>
    <!-- Background Squircle -->
    <rect width="48" height="48" rx="14" fill="url(#rmfy-bg-grad)" filter="url(#rmfy-shadow)"/>
    <!-- Subtle Inner Border -->
    <rect x="0.75" y="0.75" width="46.5" height="46.5" rx="13.25" stroke="rgba(255,255,255,0.3)" stroke-width="1.5"/>
    
    <!-- Stylized Modern Luxury Hotel Emblem & R Monogram -->
    <!-- Outer Arch / Room Frame -->
    <path d="M12 36V22C12 15.3726 17.3726 10 24 10C30.6274 10 36 15.3726 36 22V36" stroke="url(#rmfy-roof-grad)" stroke-width="3" stroke-linecap="round"/>
    
    <!-- Stylized 'R' Suite Column & Bed Horizon -->
    <path d="M18 36V18H25.5C28 18 29.5 19.5 29.5 22C29.5 24.5 28 26 25.5 26H18M24 26L30 36" stroke="#FFFFFF" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"/>
    
    <!-- Golden Compass Star / 5-Star Sparkle -->
    <path d="M34 11L35.2 13.8L38 15L35.2 16.2L34 19L32.8 16.2L30 15L32.8 13.8L34 11Z" fill="url(#rmfy-gold-grad)"/>
    <circle cx="24" cy="31" r="1.5" fill="#FCD34D"/>
</svg>
