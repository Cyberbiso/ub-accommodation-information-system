<!-- resources/views/layouts/public.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'UB ONBOARDING') - UB Accommodation and Information System</title>
    
    <!-- Vite with Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #800000 0%, #660000 100%);
        }
        .feature-card:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
        .maroon-gradient {
            background: linear-gradient(135deg, #990000 0%, #660000 100%);
        }
        .maroon-light {
            background: linear-gradient(135deg, #b30000 0%, #800000 100%);
        }
    </style>
</head>
<body class="antialiased">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center space-x-2">
                        <i class="fas fa-home text-2xl text-red-800"></i>
                        <span class="font-bold text-xl text-gray-800">UB Onboarding</span>
                    </a>
                </div>
                
                <!-- Desktop Navigation - CLEAN SINGLE LINE -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ url('/') }}" class="text-gray-700 hover:text-red-800 transition font-medium">Home</a>
                    <a href="{{ route('accommodation.hub') }}" class="text-gray-700 hover:text-red-800 transition font-medium">Accommodation Hub</a>
                    <a href="{{ route('information.hub') }}" class="text-gray-700 hover:text-red-800 transition font-medium">Information Hub</a>
                    <a href="{{ url('/faq') }}" class="text-gray-700 hover:text-red-800 transition font-medium">FAQ</a>
                    <a href="{{ url('/contact') }}" class="text-gray-700 hover:text-red-800 transition font-medium">Contact</a>
                </div>
                
                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center space-x-3">
                    @auth
                        <div class="relative group">
                            <button class="flex items-center space-x-1 text-gray-700 hover:text-red-800 transition font-medium">
                                <i class="fas fa-user-circle text-xl"></i>
                                <span>{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 hidden group-hover:block z-50 border">
                                @if(Auth::user()->isStudent())
                                    <a href="{{ url('/student/dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800">📊 Dashboard</a>
                                @elseif(Auth::user()->isLandlord())
                                    <a href="{{ url('/landlord/dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800">📊 Dashboard</a>
                                @elseif(Auth::user()->isWelfare())
                                    <a href="{{ url('/welfare/dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800">📊 Dashboard</a>
                                @elseif(Auth::user()->isAdmin())
                                    <a href="{{ url('/admin/dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800">📊 Dashboard</a>
                                @endif
                                <a href="{{ url('/profile') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800">👤 Profile</a>
                                <hr class="my-1">
                                <form method="POST" action="{{ url('/logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ url('/login') }}" class="text-gray-700 hover:text-red-800 transition font-medium px-3 py-2">Login</a>
                        <a href="{{ url('/register') }}" class="bg-red-800 text-white px-4 py-2 rounded-md hover:bg-red-900 transition font-medium">Register</a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button type="button" id="mobile-menu-button" class="text-gray-500 hover:text-red-800 focus:outline-none">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu (hidden by default) -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ url('/') }}" class="block px-3 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800 rounded-md">Home</a>
                <a href="{{ route('accommodation.hub') }}" class="block px-3 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800 rounded-md">Accommodation Hub</a>
                <a href="{{ route('information.hub') }}" class="block px-3 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800 rounded-md">Information Hub</a>
                <a href="{{ url('/faq') }}" class="block px-3 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800 rounded-md">FAQ</a>
                <a href="{{ url('/contact') }}" class="block px-3 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800 rounded-md">Contact</a>
                
                @auth
                    <hr class="my-2">
                    @if(Auth::user()->isStudent())
                        <a href="{{ url('/student/dashboard') }}" class="block px-3 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800 rounded-md">📊 Dashboard</a>
                    @elseif(Auth::user()->isLandlord())
                        <a href="{{ url('/landlord/dashboard') }}" class="block px-3 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800 rounded-md">📊 Dashboard</a>
                    @elseif(Auth::user()->isWelfare())
                        <a href="{{ url('/welfare/dashboard') }}" class="block px-3 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800 rounded-md">📊 Dashboard</a>
                    @elseif(Auth::user()->isAdmin())
                        <a href="{{ url('/admin/dashboard') }}" class="block px-3 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800 rounded-md">📊 Dashboard</a>
                    @endif
                    <a href="{{ url('/profile') }}" class="block px-3 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800 rounded-md">👤 Profile</a>
                    <form method="POST" action="{{ url('/logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-3 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800 rounded-md">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                @else
                    <hr class="my-2">
                    <a href="{{ url('/login') }}" class="block px-3 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800 rounded-md">Login</a>
                    <a href="{{ url('/register') }}" class="block px-3 py-2 bg-red-800 text-white rounded-md hover:bg-red-900">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <script>
        // Simple mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            var menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>

    @if($sharedAnnouncements->count())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="space-y-3">
                @foreach($sharedAnnouncements as $announcement)
                    <div class="rounded-2xl border px-5 py-4 {{ $announcement->priority === 'important' ? 'bg-red-50 border-red-200' : ($announcement->priority === 'warning' ? 'bg-amber-50 border-amber-200' : 'bg-blue-50 border-blue-200') }}">
                        <div class="flex items-start justify-between gap-4 flex-wrap">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $announcement->title }}</p>
                                <p class="text-sm text-gray-700 mt-1">{{ $announcement->content }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $announcement->priority === 'important' ? 'bg-red-100 text-red-800' : ($announcement->priority === 'warning' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') }}">
                                {{ ucfirst($announcement->priority) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">UB Onboarding</h3>
                    <p class="text-gray-400">Your complete pre-arrival and accommodation solution at the University of Botswana.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ url('/') }}" class="text-gray-400 hover:text-white transition">Home</a></li>
                        <li><a href="{{ route('accommodation.hub') }}" class="text-gray-400 hover:text-white transition">Accommodation Hub</a></li>
                        <li><a href="{{ route('information.hub') }}" class="text-gray-400 hover:text-white transition">Information Hub</a></li>
                        <li><a href="{{ url('/about') }}" class="text-gray-400 hover:text-white transition">About</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Support</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ url('/faq') }}" class="text-gray-400 hover:text-white transition">FAQ</a></li>
                        <li><a href="{{ url('/contact') }}" class="text-gray-400 hover:text-white transition">Contact</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Terms of Service</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact Info</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-map-marker-alt mr-2"></i> Gaborone, Botswana</li>
                        <li><i class="fas fa-phone mr-2"></i> +267 123 4567</li>
                        <li><i class="fas fa-envelope mr-2"></i> info@ub.ac.bw</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} UB Onboarding System. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
