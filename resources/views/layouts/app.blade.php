<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'UB Onboarding') }} - @yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        .app-gradient {
            background: linear-gradient(135deg, #800000 0%, #660000 100%);
        }
        .app-button {
            background: linear-gradient(135deg, #800000 0%, #660000 100%);
            transition: all 0.3s;
        }
        .app-button:hover {
            background: linear-gradient(135deg, #990000 0%, #800000 100%);
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center space-x-2">
                        <i class="fas fa-home text-2xl text-red-800"></i>
                        <span class="font-bold text-xl text-gray-800">UB Onboarding</span>
                    </a>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="relative group">
                        <button class="flex items-center space-x-1 text-gray-700 hover:text-red-800 transition font-medium">
                            <i class="fas fa-user-circle text-xl"></i>
                            <span>{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 hidden group-hover:block z-50 border">
                            @if(Auth::user()->isStudent())
                                <a href="{{ route('student.home') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800">🏠 Home</a>
                                <a href="{{ route('student.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800">📊 Dashboard</a>
                            @elseif(Auth::user()->isLandlord())
                                <a href="{{ route('landlord.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800">📊 Dashboard</a>
                            @elseif(Auth::user()->isWelfare())
                                <a href="{{ route('welfare.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800">📊 Dashboard</a>
                            @elseif(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800">📊 Dashboard</a>
                            @endif
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800">👤 Profile</a>
                            <hr class="my-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Heading -->
    @if (isset($header))
        <header class="app-gradient text-white">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endif

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-gray-400">&copy; {{ date('Y') }} UB Onboarding System. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>