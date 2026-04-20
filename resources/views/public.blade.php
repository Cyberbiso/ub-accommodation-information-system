<!-- resources/views/layouts/public.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'UniStay') - UniStay</title>
    
    <!-- Vite with Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
    .hero-gradient {
        /* Change from indigo/purple to maroon */
        background: linear-gradient(135deg, #800000 0%, #660000 100%);
        /* #800000 = maroon, #660000 = darker maroon */
    }
    .feature-card:hover {
        transform: translateY(-5px);
        transition: transform 0.3s ease;
    }
    
    /* Optional: Add more maroon variations */
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
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center space-x-2">
                        <i class="fas fa-home text-2xl text-red-800"></i>
                        <span class="font-bold text-xl text-gray-800">UniStay</span>
                    </a>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ url('/') }}" class="text-gray-700 hover:text-red-800 transition">Home</a>
                    <a href="{{ url('/about') }}" class="text-gray-700 hover:text-red-800 transition">About</a>
                    <a href="{{ url('/properties') }}" class="text-gray-700 hover:text-red-800 transition">Properties</a>
                    <a href="{{ url('/accommodations') }}" class="text-gray-700 hover:text-red-800 transition">On-Campus</a>
                    <a href="{{ url('/faq') }}" class="text-gray-700 hover:text-red-800 transition">FAQ</a>
                    <a href="{{ url('/contact') }}" class="text-gray-700 hover:text-red-800 transition">Contact</a>
                </div>
                
                <div class="flex items-center space-x-4">
                    @auth
                        <span class="text-gray-600">Welcome, {{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-red-800 transition">
                                <i class="fas fa-sign-out-alt mr-1"></i>Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ url('/login') }}" class="text-gray-700 hover:text-red-800 transition">
                            <i class="fas fa-sign-in-alt mr-1"></i>Login
                        </a>
                        <a href="{{ url('/register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                            <i class="fas fa-user-plus mr-1"></i>Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

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
                    <h3 class="text-lg font-semibold mb-4">UniStay</h3>
                    <p class="text-gray-400">Your trusted platform for student housing at the University of Botswana.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ url('/') }}" class="text-gray-400 hover:text-white transition">Home</a></li>
                        <li><a href="{{ url('/about') }}" class="text-gray-400 hover:text-white transition">About</a></li>
                        <li><a href="{{ url('/properties') }}" class="text-gray-400 hover:text-white transition">Properties</a></li>
                        <li><a href="{{ url('/accommodations') }}" class="text-gray-400 hover:text-white transition">On-Campus</a></li>
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
                <p>&copy; {{ date('Y') }} UniStay. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>