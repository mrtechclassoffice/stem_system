<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Student Portal') — STEM Academy</title>
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
<body class="min-h-screen bg-slate-950 text-slate-100">
    
    <!-- Top Nav -->
    <header class="sticky top-0 z-40 bg-slate-900/95 backdrop-blur border-b border-slate-800">
        <div class="max-w-6xl mx-auto px-4 flex items-center justify-between h-14">
            <!-- Logo -->
            <a href="{{ route('student.dashboard') }}" class="flex items-center gap-2.5">
                <img src="{{ asset('images/logo.png') }}" alt="STEM Academy Logo" class="w-8 h-8 object-contain">
                <span class="text-white font-bold text-sm hidden sm:block">STEM Academy</span>
            </a>

            <!-- Desktop nav -->
            <nav class="hidden md:flex items-center gap-1">
                @php
                    $navItems = [
                        ['route' => 'student.dashboard', 'label' => 'Dashboard', 'icon' => 'layout-dashboard'],
                        ['route' => 'student.materials.index', 'label' => 'My Materials', 'icon' => 'book-open'],
                        ['route' => 'student.media.index', 'label' => 'Media Gallery', 'icon' => 'film'],
                        ['route' => 'student.submissions.index', 'label' => 'My Submissions', 'icon' => 'send'],
                        ['route' => 'student.profile.show', 'label' => 'My Profile', 'icon' => 'user']
                    ];
                @endphp

                @foreach($navItems as $item)
                    @php
                        $isActive = request()->routeIs($item['route']);
                    @endphp
                    <a href="{{ route($item['route']) }}" 
                       class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-150 {{ $isActive ? 'bg-violet-600/20 text-violet-400' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4"></i>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <!-- Right actions -->
            <div class="flex items-center gap-2">
                @if(auth()->check() && auth()->user()->student)
                    <span class="hidden sm:block text-slate-400 text-sm mr-2">
                        Hi, <span class="text-white font-medium">{{ explode(' ', auth()->user()->student->full_name)[0] }}</span>
                    </span>
                @endif
                <form action="{{ route('logout') }}" method="POST" class="hidden md:block m-0">
                    @csrf
                    <button type="submit" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm text-slate-400 hover:text-red-400 hover:bg-red-400/10 transition-all duration-150">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        Sign Out
                    </button>
                </form>
                <button id="mobile-hamburger" class="md:hidden text-slate-400 hover:text-white transition-colors p-1">
                    <i data-lucide="menu" id="menu-icon" class="w-5 h-5"></i>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-slate-800 bg-slate-900 px-4 py-3 space-y-1">
            @foreach($navItems as $item)
                @php
                    $isActive = request()->routeIs($item['route']);
                @endphp
                <a href="{{ route($item['route']) }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ $isActive ? 'bg-violet-600/20 text-violet-400' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4"></i>
                    {{ $item['label'] }}
                </a>
            @endforeach
            <form action="{{ route('logout') }}" method="POST" class="m-0 pt-2 border-t border-slate-800">
                @csrf
                <button type="submit" class="flex items-center gap-3 px-3 py-2.5 w-full rounded-lg text-sm font-medium text-slate-400 hover:text-red-400 hover:bg-red-400/10 transition-all">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    Sign Out
                </button>
            </form>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-6 lg:py-8">
        <!-- Success Alert -->
        @if(session('success'))
            <div class="mb-6 flex items-center gap-2 bg-emerald-500/10 border border-emerald-500/20 rounded-xl px-4 py-3 text-emerald-400 text-sm">
                <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <script>
        // Init Lucide
        lucide.createIcons();

        // Responsive Mobile Menu
        const mobileHamburger = document.getElementById('mobile-hamburger');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');

        mobileHamburger?.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            const isHidden = mobileMenu.classList.contains('hidden');
            menuIcon.setAttribute('data-lucide', isHidden ? 'menu' : 'x');
            lucide.createIcons();
        });
    </script>
</body>
</html>
