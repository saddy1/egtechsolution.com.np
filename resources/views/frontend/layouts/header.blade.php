<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('') }}">
    <link rel="icon" href="{{ asset('') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <link rel="preconnect" href="https://fonts.gstatic.com">

    @stack('head')
    <title>@yield('title', 'Default Title')</title>
<meta name="description" content="@yield('meta_description', 'Default description')">
<meta name="keywords" content="@yield('meta_keywords', 'Default keywords')">


@vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'theme-green': '#C1D9D9',
                        'theme-dark': '#011b08',
                        'theme-orange': '#e87316'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 font-sans">
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
                                        <a href="shop.html"
                                            class="nav-link menu-title text-white hover:text-gray-50 font-medium py-2 px-3 rounded-lg hover:bg-white/20 transition-all duration-200">
                                            Shop
                                        </a>
                                    </li>
                                    <li>
                                        <a href="shop.html"
                                            class="nav-link menu-title  text-white hover:text-gray-50 font-medium py-2 px-3 rounded-lg hover:bg-white/20 transition-all duration-200">
                                            Category
                                        </a>
                                    </li>
                                    <li>
                                        <a href="about-us.html"
                                            class="nav-link menu-title  text-white hover:text-gray-50 font-medium py-2 px-3 rounded-lg hover:bg-white/20 transition-all duration-200">
                                            About Us
                                        </a>
                                    </li>
                                    <li>
                                        <a href="contact-us.html"
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
                                <div
                                    class="search-box mx-3 bg-theme-orange p-2 rounded-lg cursor-pointer bg-white hover:bg-orange-600 transition-colors duration-200 mr-5">
                                    <i data-feather="search"
                                        class="w-2 h-2 sm:w-4 sm:h-4 md:w-5 md:h-5 lg:w-5 lg:h-5 text-black"></i>
                                </div>

                            </li>
                            @if (Auth::check())
                                <li class="onhover-dropdown wislist-dropdown relative group">
                                    <div class="cart-media">
                                        <a href="{{ route('cart.show', Auth::id()) }}"
                                            class="flex items-center p-2 bg-white hover:bg-red-400/20 rounded-lg transition-colors duration-200">
                                            <i data-feather="shopping-cart"
                                                class="w-2 h-2 sm:w-4 sm:h-4 md:w-5 md:h-5 lg:w-6 lg:h-5 text-gray-800"></i>
                                            <span id="cart-count"
                                                class="absolute -top-1 -right-1 bg-theme-orange bg-red-600 text-white text-xs rounded-full w-3 h-3 sm:w-4 sm:h-4 md:w-4 md:h-4 lg:w-4 lg:h-4 flex items-center justify-center font-medium">
                                                {{ $cartCount }}
                                            </span>
                                        </a>
                                    </div>
                                </li>
                                <li class="onhover-dropdown relative group">
                                    <div
                                        class="cart-media name-usr bg-theme-dark text-white px-3 py-2 rounded-lg text-[8px] text-center sm:text-sm md:text-base lg:text-lg uppercase leading-tight ml-5 hover:bg-gray-800 transition-colors duration-200 cursor-pointer">
                                        <i data-feather="user" class="w-4 h-4 inline mr-2"></i>{{ Auth::user()->name }}
                                    </div>

                                    <div
                                        class="onhover-div profile-dropdown absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                        <ul class="py-2">
                                            <li class="border-b border-gray-200 last:border-b-0">
                                                <a href="{{ route('logout') }}"
                                                    class="block px-5 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-red-600 transition-colors duration-150">
                                                    Logout
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            @else
                                <li class="onhover-dropdown relative group">
                                    <div
                                        class="cart-media name-usr bg-theme-dark bg-white  text-black px-2 py-2 rounded-lg text-xs uppercase leading-tight ml-5 hover:bg-gray-200 transition-colors duration-200 cursor-pointer">
                                        <i data-feather="user"
                                            class=" w-2 h-2 sm:w-4 sm:h-4 md:w-5 md:h-5 lg:w-5 lg:h-5inline mr-2"></i>
                                    </div>
                                    <div
                                        class="onhover-div profile-dropdown absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                        <ul class="py-2">
                                            <li class="border-b border-gray-200 last:border-b-0">
                                                <a href="{{ url('login') }}"
                                                    class="block px-5 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors duration-150">
                                                    Login
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            @endif

                            <!-- Mobile Menu Button -->
                            <li class="lg:hidden">
                                <button id="mobileMenuBtn"
                                    class="p-2 text-white hover:bg-red-500 rounded-lg transition-colors duration-200">
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
                <a href="shop.html"
                    class="block py-3 px-4 text-gray-700 hover:bg-theme-green/20 hover:text-gray-900 rounded-lg font-medium transition-colors duration-150">Shop</a>
                <a href="shop.html"
                    class="block py-3 px-4 text-gray-700 hover:bg-theme-green/20 hover:text-gray-900 rounded-lg font-medium transition-colors duration-150">Category</a>
                <a href="about-us.html"
                    class="block py-3 px-4 text-gray-700 hover:bg-theme-green/20 hover:text-gray-900 rounded-lg font-medium transition-colors duration-150">About
                    Us</a>
                <a href="contact-us.html"
                    class="block py-3 px-4 text-gray-700 hover:bg-theme-green/20 hover:text-gray-900 rounded-lg font-medium transition-colors duration-150">Contact
                    Us</a>
            </nav>
        </div>
    </div>

    <!-- Mobile Bottom Menu -->
    <div class="mobile-menu fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 sm:hidden z-40">
        <ul class="grid grid-cols-5 py-2">
            <li>
                <a href="demo3.php" class="flex flex-col items-center py-2 text-theme-orange">
                    <i data-feather="home" class="w-5 h-5"></i>
                    <span class="text-xs font-medium mt-1">Home</span>
                </a>
            </li>
            <li>
                <a href="javascript:void(0)"
                    class="flex flex-col items-center py-2 text-gray-600 hover:text-theme-orange transition-colors duration-150">
                    <i data-feather="align-justify" class="w-5 h-5"></i>
                    <span class="text-xs font-medium mt-1">Category</span>
                </a>
            </li>
            <li class="onhover-dropdown wislist-dropdown">
                <div class="cart-media">
                    <a href="{{ route('cart.show', Auth::id()) }}"
                        class="flex flex-col items-center py-2 text-gray-600 hover:text-theme-orange transition-colors duration-150 relative">
                        <i data-feather="shopping-cart" class="w-5 h-5"></i>
                        <span class="text-xs font-medium mt-1">Cart</span>
                        <span id="cart-count"
                            class="absolute -top-1 right-3 bg-theme-orange text-white text-xs rounded-full h-4 w-4 flex items-center justify-center font-medium">
                            {{-- {{ $cartCount }} --}}
                        </span>
                    </a>
                </div>
            </li>
            <li>
                <a href="javascript:void(0)"
                    class="flex flex-col items-center py-2 text-gray-600 hover:text-theme-orange transition-colors duration-150">
                    <i data-feather="heart" class="w-5 h-5"></i>
                    <span class="text-xs font-medium mt-1">Wishlist</span>
                </a>
            </li>
            <li>
                <a href="user-dashboard.php"
                    class="flex flex-col items-center py-2 text-gray-600 hover:text-theme-orange transition-colors duration-150">
                    <i data-feather="user" class="w-5 h-5"></i>
                    <span class="text-xs font-medium mt-1">Account</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Demo Content -->


    <script>
        // Initialize Feather Icons
        feather.replace();

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
