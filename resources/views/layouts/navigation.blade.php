<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 fixed top-0 left-0 right-0 z-10">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Navigation Links -->
                    <div class="flex space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <x-nav-link :href="route('mypage')" :active="request()->routeIs('mypage')">
                            {{ __('Home') }}
                        </x-nav-link>
                    </div>

                    <!-- Center Link -->
                    <div class="flex items-center justify-center">
                        <x-nav-link :href="route('shared')" :active="request()->routeIs('shared')" class="dark:text-white text-xl font-bold">
                            {{ __('みんなの共有スポット') }}
                        </x-nav-link>
                    </div>

                    <!-- Right Side Of Navbar -->
                    <div class="flex items-center sm:ml-6">
                        @auth
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex flex-col items-center text-sm font-medium text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300">
                                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">メニュー</span>
                                </button>
                            </x-slot>
                    
                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('プロフィール編集') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('spot.create')">
                                    {{ __('新規スポット登録') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('spot.favorite')">
                                    {{ __('お気に入りリスト') }}
                                </x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                        @endauth
                    </div>                    
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="flex-grow mt-16 sm:mt-0" style="padding-bottom: 100px;">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-800 shadow fixed bottom-0 left-0 right-0">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <p class="text-gray-600">
                        &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
                    </p>
                    @auth
                    @endauth
                </div>
            </div>
        </footer>
    </div>
</body>
</html>

<style>
    body {
        margin-top: 60px; /* ヘッダーの高さを考慮したページの上部マージン */
    }
</style>
