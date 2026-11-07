<!DOCTYPE html>
<html lang="en-NP">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <link rel="preconnect" href="https://fonts.gstatic.com">

    <title>@yield('title', 'EG Tech Solution | Computer Shop in Dhangadhi')</title>
    <meta name="description" content="@yield('meta_description', 'EG Tech Solution is a computer and electronics shop in Dhangadhi, Kailali, Nepal.')">
    <meta name="keywords" content="@yield('meta_keywords', 'computer shop Dhangadhi, laptop shop Dhangadhi, electronics Kailali')">
    <meta name="robots" content="{{ request()->query() ? 'noindex, follow' : 'index, follow' }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="en_NP">
    <meta property="og:site_name" content="EG Tech Solution">
    <meta property="og:title" content="@yield('title', 'EG Tech Solution | Computer Shop in Dhangadhi')">
    <meta property="og:description" content="@yield('meta_description', 'EG Tech Solution is a computer and electronics shop in Dhangadhi, Kailali, Nepal.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('assets/eg_tech_logo.png') }}">
    <meta name="twitter:card" content="summary_large_image">
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org', '@type' => 'ComputerStore', 'name' => 'EG Tech Solution',
        'url' => url('/'), 'image' => asset('assets/eg_tech_logo.png'), 'telephone' => '+9779864830276',
        'email' => 'egtechsolution1@gmail.com',
        'address' => ['@type' => 'PostalAddress', 'addressLocality' => 'Dhangadhi', 'addressRegion' => 'Kailali', 'addressCountry' => 'NP'],
        'areaServed' => ['@type' => 'City', 'name' => 'Dhangadhi'],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @stack('structured_data')


@vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js"></script>
    {{-- Brand colours live in resources/css/app.css (@theme); Tailwind v4 ignores inline tailwind.config. --}}
</head>

<body class="bg-gray-50 font-sans pb-16 sm:pb-0">
    <header class="sticky top-0 z-50" id="home">
        <div class="bg-[rgb(0,46,91)] shadow-2xs">
            <div class="container mx-auto px-4 lg:px-6">
                <div class="flex items-center justify-between h-16 md:h-20">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <div class="brand-logo">
                            <a href="/">
                                <img src="{{ asset('assets/eg_tech_logo_white.png') }}"
                                    class="h-15 sm:h-10 md:h-20 w-auto max-w-[110px] md:max-w-[185px] object-contain hover:scale-105 transition-transform duration-200"
                                    alt="logo">
                            </a>
                        </div>
                    </div>

                    <!-- Desktop Navigation -->
                    <nav class="hidden lg:flex items-center space-x-8">
                        <div class="main-navbar">
                            <div id="mainnav">
                                <div class="toggle-nav lg:hidden">
                                    <i class="fa fa-bars sidebar-bar text-2xl cursor-pointer"></i>
                                </div>
                                <ul class="nav-menu flex items-center space-x-6">
                                    <li class="back-btn hidden xl:hidden">
                                        <div class="close-btn flex items-center">
                                            Menu
                                            <span class="mobile-back ml-2"><i class="fa fa-angle-left"></i></span>
                                        </div>
                                    </li>
                                    <li>
                                        <a href="/"
                                            class="nav-link menu-title text-white hover:text-gray-50 font-medium py-2 px-3 rounded-lg hover:bg-white/20 transition-all duration-200">
                                            Home
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('shop') }}"
                                            class="nav-link menu-title text-white hover:text-gray-50 font-medium py-2 px-3 rounded-lg hover:bg-white/20 transition-all duration-200">
                                            Shop
                                        </a>
                                    </li>
                                  
                                    <li>
                                        <a href="{{route('about')}}"
                                            class="nav-link menu-title  text-white hover:text-gray-50 font-medium py-2 px-3 rounded-lg hover:bg-white/20 transition-all duration-200">
                                            About Us
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('contact') }}"
                                            class="nav-link menu-title  text-white hover:text-gray-50 font-medium py-2 px-3 rounded-lg hover:bg-white/20 transition-all duration-200">
                                            Contact Us
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>

                    <!-- Right Menu -->
                    <div class="menu-right">
                        <ul class="flex items-center space-x-1 md:space-x-4">
                            <li>
                                <button type="button" aria-label="Search products"
                                    class="search-box flex h-11 w-11 items-center justify-center rounded-lg bg-white text-brand-navy hover:bg-slate-100 transition-colors duration-200">
                                    <i data-feather="search" class="w-5 h-5"></i>
                                </button>
                            </li>
                            @if (Auth::check())
                                <li class="relative">
                                    <a href="{{ route('cart.show') }}" aria-label="Cart ({{ $cartCount }} items)"
                                        class="flex h-11 w-11 items-center justify-center rounded-lg bg-white text-brand-navy hover:bg-slate-100 transition-colors duration-200">
                                        <i data-feather="shopping-cart" class="w-5 h-5"></i>
                                    </a>
                                    <span id="cart-count"
                                        class="pointer-events-none absolute -top-1.5 -right-1.5 flex h-5 min-w-5 items-center justify-center rounded-full bg-brand-orange px-1 text-xs font-semibold text-white">
                                        {{ $cartCount }}
                                    </span>
                                </li>
                                <li class="relative" data-dropdown>
                                    <button type="button" data-dropdown-toggle aria-expanded="false" aria-haspopup="true"
                                        class="flex h-11 items-center gap-2 rounded-lg bg-white/10 px-3 text-sm font-medium text-white hover:bg-white/20 transition-colors duration-200">
                                        <i data-feather="user" class="w-5 h-5"></i>
                                        <span class="hidden max-w-[10rem] truncate sm:inline">{{ Auth::user()->name }}</span>
                                    </button>
                                    <div data-dropdown-menu
                                        class="absolute right-0 mt-2 hidden w-56 rounded-lg border border-gray-200 bg-white py-2 shadow-xl z-50">
                                        <a href="{{ route('cart.show') }}" class="block px-5 py-3 text-sm text-gray-700 hover:bg-gray-50">My cart</a>
                                        <a href="{{ route('logout') }}" class="block px-5 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-red-600">Log out</a>
                                    </div>
                                </li>
                            @else
                                <li>
                                    <a href="{{ url('login') }}"
                                        class="flex h-11 items-center gap-2 rounded-lg bg-white/10 px-3 text-sm font-medium text-white hover:bg-white/20 transition-colors duration-200">
                                        <i data-feather="user" class="w-5 h-5"></i>
                                        <span class="hidden sm:inline">Log in</span>
                                    </a>
                                </li>
                            @endif

                            <!-- Mobile Menu Button -->
                            <li class="lg:hidden">
                                <button id="mobileMenuBtn" type="button" aria-label="Open menu"
                                    class="flex h-11 w-11 items-center justify-center rounded-lg text-white hover:bg-white/15 transition-colors duration-200">
                                    <i data-feather="menu" class="w-6 h-6"></i>
                                </button>
                            </li>
                        </ul>
                    </div>

                    <!-- Search Full -->
                    <div
                        class="search-full fixed top-16 md:top-20 left-0 right-0 bg-white border-b border-gray-200 transform transition-all duration-300 ease-in-out max-h-0 overflow-hidden opacity-0 z-40">
                        <form method="GET" class="search-full" action="{{ url('/') }}">
                            <div class="container mx-auto px-4 lg:px-6 py-4">
                                <div class="input-group relative max-w-2xl mx-auto flex">
                                    <span
                                        class="input-group-text absolute left-3 top-1/2 transform -translate-y-1/2 z-10">
                                        <i data-feather="search" class="w-5 h-5 text-gray-400"></i>
                                    </span>
                                    <input type="text" name="search"
                                        class="form-control search-type w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-theme-orange focus:border-theme-orange text-gray-900 placeholder-gray-500"
                                        placeholder="Search here..">
                                    <span
                                        class="input-group-text close-search absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer hover:bg-gray-100 p-1 rounded">
                                        <i data-feather="x" class="w-5 h-5 text-gray-400 hover:text-gray-600"></i>
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Menu -->
    <div id="mobileMenu"
        class="fixed inset-0 bg-black/50 z-50 lg:hidden transform translate-x-full transition-transform duration-300 ease-in-out">
        <div class="fixed inset-y-0 right-0 max-w-xs w-full bg-white shadow-xl">
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Menu</h3>
                <button id="closeMobileMenu"
                    class="p-2 text-gray-400 hover:text-red-600 hover:bg-gray-200 rounded-lg">
                    <i data-feather="x" class="w-5 h-5"></i>
                </button>
            </div>
            <nav class="p-4 space-y-2">
                <a href="/"
                    class="block py-3 px-4 text-gray-700 hover:bg-theme-green/20 hover:text-gray-900 rounded-lg font-medium transition-colors duration-150">Home</a>
                <a href="{{ route('shop') }}"
                    class="block py-3 px-4 text-gray-700 hover:bg-theme-green/20 hover:text-gray-900 rounded-lg font-medium transition-colors duration-150">Shop</a>
                <a href="{{ route('shop') }}"
                    class="block py-3 px-4 text-gray-700 hover:bg-theme-green/20 hover:text-gray-900 rounded-lg font-medium transition-colors duration-150">Category</a>
                <a href="{{route('about')}}"
                    class="block py-3 px-4 text-gray-700 hover:bg-theme-green/20 hover:text-gray-900 rounded-lg font-medium transition-colors duration-150">About
                    Us</a>
                <a href="{{ route('contact') }}"
                    class="block py-3 px-4 text-gray-700 hover:bg-theme-green/20 hover:text-gray-900 rounded-lg font-medium transition-colors duration-150">Contact
                    Us</a>
            </nav>
        </div>
    </div>

    <!-- Mobile Bottom Menu -->
    <div class="mobile-menu fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 sm:hidden z-40">
        @php
            $bottomItem = 'flex flex-col items-center py-2 transition-colors duration-150';
            $on  = 'text-brand-orange';
            $off = 'text-gray-600 hover:text-brand-orange';
        @endphp
        <ul class="grid grid-cols-5 py-1">
            <li>
                <a href="{{ route('home') }}" class="{{ $bottomItem }} {{ request()->routeIs('home') ? $on : $off }}">
                    <i data-feather="home" class="w-5 h-5"></i>
                    <span class="text-xs font-medium mt-1">Home</span>
                </a>
            </li>
            <li>
                <a href="{{ route('shop') }}" class="{{ $bottomItem }} {{ request()->routeIs('shop') ? $on : $off }}">
                    <i data-feather="grid" class="w-5 h-5"></i>
                    <span class="text-xs font-medium mt-1">Shop</span>
                </a>
            </li>
            <li>
                <a href="{{ Auth::check() ? route('cart.show') : url('login') }}" class="{{ $bottomItem }} {{ $off }} relative">
                    <i data-feather="shopping-cart" class="w-5 h-5"></i>
                    <span class="text-xs font-medium mt-1">Cart</span>
                    @if (Auth::check() && ($cartCount ?? 0) > 0)
                        <span class="absolute -top-0.5 right-3 flex h-4 min-w-4 items-center justify-center rounded-full bg-brand-orange px-1 text-[10px] font-semibold text-white">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
            </li>
            <li>
                <a href="tel:+9779864830276" class="{{ $bottomItem }} {{ $off }}">
                    <i data-feather="phone" class="w-5 h-5"></i>
                    <span class="text-xs font-medium mt-1">Call</span>
                </a>
            </li>
            <li>
                <a href="{{ Auth::check() ? route('cart.show') : url('login') }}" class="{{ $bottomItem }} {{ $off }}">
                    <i data-feather="user" class="w-5 h-5"></i>
                    <span class="text-xs font-medium mt-1">{{ Auth::check() ? 'Account' : 'Log in' }}</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Demo Content -->


    <script>
        // Initialize Feather Icons
        feather.replace();

        // Account menu: opens on click/tap and keyboard, closes on outside click or Escape
        document.querySelectorAll('[data-dropdown]').forEach((wrap) => {
            const btn = wrap.querySelector('[data-dropdown-toggle]');
            const menu = wrap.querySelector('[data-dropdown-menu]');
            const set = (open) => { menu.classList.toggle('hidden', !open); btn.setAttribute('aria-expanded', open); };
            btn.addEventListener('click', (e) => { e.stopPropagation(); set(menu.classList.contains('hidden')); });
            document.addEventListener('click', (e) => { if (!wrap.contains(e.target)) set(false); });
            document.addEventListener('keydown', (e) => { if (e.key === 'Escape') { set(false); } });
        });

        // Search functionality - maintaining original logic
        const searchBox = document.querySelector('.search-box');
        const searchFull = document.querySelector('.search-full');
        const closeSearch = document.querySelector('.close-search');
        let isSearchOpen = false;

        function openSearch() {
            isSearchOpen = true;
            searchFull.classList.remove('max-h-0', 'opacity-0');
            searchFull.classList.add('max-h-32', 'opacity-100');
            setTimeout(() => {
                searchFull.querySelector('input').focus();
            }, 150);
        }

        function closeSearchBar() {
            isSearchOpen = false;
            searchFull.classList.remove('max-h-32', 'opacity-100');
            searchFull.classList.add('max-h-0', 'opacity-0');
        }

        searchBox.addEventListener('click', (e) => {
            e.preventDefault();
            if (isSearchOpen) {
                closeSearchBar();
            } else {
                openSearch();
            }
        });

        closeSearch.addEventListener('click', (e) => {
            e.preventDefault();
            closeSearchBar();
        });

        // Mobile menu functionality
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const closeMobileMenu = document.getElementById('closeMobileMenu');

        mobileMenuBtn.addEventListener('click', (e) => {
            e.preventDefault();
            mobileMenu.classList.remove('translate-x-full');
            document.body.style.overflow = 'hidden';
        });

        closeMobileMenu.addEventListener('click', (e) => {
            e.preventDefault();
            mobileMenu.classList.add('translate-x-full');
            document.body.style.overflow = 'auto';
        });

        // Close mobile menu when clicking outside
        mobileMenu.addEventListener('click', (e) => {
            if (e.target === mobileMenu) {
                mobileMenu.classList.add('translate-x-full');
                document.body.style.overflow = 'auto';
            }
        });

        // Close search when clicking outside
        document.addEventListener('click', (e) => {
            if (isSearchOpen && !searchFull.contains(e.target) && !searchBox.contains(e.target)) {
                closeSearchBar();
            }
        });

        // Handle search form submission
        searchFull.querySelector('input').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                // Form will submit naturally due to Laravel form action
            }
        });

        // Close search with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && isSearchOpen) {
                closeSearchBar();
            }
        });

        // Re-initialize Feather icons
        setTimeout(() => {
            feather.replace();
        }, 100);
    </script>
