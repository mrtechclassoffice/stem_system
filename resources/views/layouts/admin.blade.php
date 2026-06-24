<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Portal') — STEM Academy</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        h1, h2, h3 {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-950 flex text-slate-100">
    
    <!-- Sidebar -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 border-r border-slate-800 transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:flex lg:flex-col">
        <!-- Logo -->
        <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-800">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center">
                <i data-lucide="graduation-cap" class="w-5 h-5 text-white"></i>
            </div>
            <div>
                <p class="text-white font-bold text-sm">STEM Academy</p>
                <p class="text-slate-400 text-xs">Admin Portal</p>
            </div>
        </div>

        <!-- Nav -->
        <nav class="flex-1 px-3 py-4 space-y-1">
            @php
                $navItems = [
                    ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'layout-dashboard'],
                    ['route' => 'admin.students.index', 'label' => 'Students', 'icon' => 'users'],
                    ['route' => 'admin.materials.create', 'label' => 'Upload Materials', 'icon' => 'upload'],
                    ['route' => 'admin.media.create', 'label' => 'Media Gallery', 'icon' => 'film'],
                    ['route' => 'admin.payments.index', 'label' => 'Payments', 'icon' => 'credit-card']
                ];
            @endphp

            @foreach($navItems as $item)
                @php
                    $isActive = request()->routeIs($item['route']) || (str_contains($item['route'], 'students') && request()->routeIs('admin.students.*'));
                @endphp
                <a href="{{ route($item['route']) }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 {{ $isActive ? 'bg-violet-600/20 text-violet-400 border border-violet-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    <i data-lucide="{{ $item['icon'] }}" class="w-4.5 h-4.5"></i>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <!-- Sign out -->
        <div class="px-3 py-4 border-t border-slate-800">
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="flex items-center gap-3 px-3 py-2.5 w-full rounded-lg text-sm font-medium text-slate-400 hover:text-red-400 hover:bg-red-400/10 transition-all duration-150">
                    <i data-lucide="log-out" class="w-4.5 h-4.5"></i>
                    Sign Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Overlay -->
    <div id="overlay" class="fixed inset-0 z-40 bg-black/60 hidden lg:hidden"></div>

    <!-- Main content -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Mobile topbar -->
        <header class="lg:hidden flex items-center gap-3 px-4 py-3 bg-slate-900 border-b border-slate-800">
            <button id="hamburger" class="text-slate-400 hover:text-white transition-colors">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
            <div class="flex items-center gap-2">
                <i data-lucide="graduation-cap" class="w-5 h-5 text-violet-400"></i>
                <span class="text-white font-bold text-sm">STEM Academy</span>
            </div>
        </header>

        <main class="flex-1 overflow-auto p-4 lg:p-8">
            <!-- Toast notification for status success -->
            @if(session('success'))
                <div class="mb-6 flex items-center gap-2 bg-emerald-500/10 border border-emerald-500/20 rounded-xl px-4 py-3 text-emerald-400 text-sm">
                    <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        // Init Lucide
        lucide.createIcons();

        // Responsive Sidebar Drawer
        const hamburger = document.getElementById('hamburger');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        hamburger?.addEventListener('click', toggleSidebar);
        overlay?.addEventListener('click', toggleSidebar);
    </script>
</body>
</html>
