<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Roomify - Cari Hotel & Tempat Menginap')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800">

    <!-- NAVBAR -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <div class="bg-indigo-600 text-white p-2 rounded-xl">
                        <i class="fa-solid fa-hotel text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">Roomify</span>
                </a>
                <!-- Right Action -->
                <div class="flex items-center gap-4">
                    <button class="relative p-2 text-slate-600 hover:text-indigo-600 transition">
                        <i class="fa-regular fa-heart text-xl"></i>
                        <span id="favCount" class="absolute -top-1 -right-1 bg-rose-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">0</span>
                    </button>
                    <a href="{{ route('login') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition shadow-md shadow-indigo-100">
                        Masuk
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- CONTENT DINAMIS -->
    <main>
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>