
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"/>


        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="h-screen flex">
            <!-- Sidebar -->
            <div class="hidden md:flex w-64 bg-gray-800">
                <div class="flex flex-col flex-grow">
                    <div class="flex items-center justify-center h-16 bg-gray-900">
                        <span class="text-white font-bold text-xl uppercase">la Tansa 2</span>
                    </div>
                    <div class="flex-grow">
                        <nav class="flex-1 px-2 pb-4 space-y-2 mt-4">
                            <a href="{{ route('dashboard') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'text-white bg-gray-900' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                <span class="ml-3">Dashboard</span>
                            </a>
                            <a href="{{ route('santri.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('santri.index', 'santri.show', 'santri.edit') ? 'text-white bg-gray-900' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                <span class="ml-3">Data Santri</span>
                            </a>
                            <a href="{{ route('santri.create') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('santri.create') ? 'text-white bg-gray-900' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                <span class="ml-3">Input Santri Baru</span>
                            </a>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex flex-col flex-1 overflow-hidden">
                @include('layouts.navigation')

                <main class="flex-1 overflow-x-hidden overflow-y-auto">
                    <div class="container mx-auto px-6 py-8">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
