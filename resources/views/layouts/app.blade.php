<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'UB-UniStay') }} - @yield('title')</title>

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
                        <span class="font-bold text-xl text-gray-800">UB-UniStay</span>
                    </a>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="relative group">
                        <button class="relative flex items-center justify-center w-10 h-10 rounded-full border border-gray-200 text-gray-700 hover:text-red-800 hover:border-red-200 transition">
                            <i class="fas fa-bell"></i>
                            @php $unreadCount = $sharedNotifications->filter(fn ($notification) => $notification->read_at === null)->count(); @endphp
                            @if($unreadCount)
                                <span class="absolute -top-1 -right-1 min-w-[20px] h-5 px-1 rounded-full bg-red-700 text-white text-[10px] font-bold flex items-center justify-center">{{ $unreadCount }}</span>
                            @endif
                        </button>
                        <div class="absolute right-0 mt-2 w-96 bg-white rounded-2xl shadow-lg py-2 hidden group-hover:block z-50 border">
                            <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900">Notifications</p>
                                    <p class="text-xs text-gray-500">Read updates from admin, welfare, and bookings</p>
                                </div>
                                @if($sharedNotifications->count())
                                    <form method="POST" action="{{ route('notifications.read-all') }}">
                                        @csrf
                                        <button type="submit" class="text-xs font-semibold text-red-800 hover:underline">Mark all read</button>
                                    </form>
                                @endif
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                @forelse($sharedNotifications as $notification)
                                    <div class="px-4 py-3 border-b border-gray-100 last:border-b-0 {{ $notification->read_at ? 'bg-white' : 'bg-red-50/40' }}">
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="min-w-0">
                                                <p class="font-semibold text-sm text-gray-900">{{ $notification->title }}</p>
                                                <p class="text-sm text-gray-600 mt-1">{{ $notification->body }}</p>
                                                <p class="text-xs text-gray-400 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                                                @if($notification->url)
                                                    <a href="{{ $notification->url }}" class="inline-flex mt-2 text-sm text-red-800 hover:underline">Open</a>
                                                @endif
                                            </div>
                                            @if(!$notification->read_at)
                                                <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                                    @csrf
                                                    <button type="submit" class="text-xs font-semibold text-gray-500 hover:text-red-800">Read</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="px-4 py-6 text-sm text-gray-500 text-center">No notifications yet.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

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
                                <a href="{{ route('student.properties') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800">🏘️ Off-Campus</a>
                                <a href="{{ route('student.bookings') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800">🧾 My Bookings</a>
                                <a href="{{ route('student.enquiries') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800">✉️ Enquiries</a>
                                <a href="{{ route('student.support') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800">🛟 Help Desk</a>
                            @elseif(Auth::user()->isLandlord())
                                <a href="{{ route('landlord.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800">📊 Dashboard</a>
                                <a href="{{ route('landlord.verification') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800">✅ Verification</a>
                                <a href="{{ route('landlord.properties') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800">🏘️ Properties</a>
                                <a href="{{ route('landlord.bookings') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800">🧾 Bookings</a>
                                <a href="{{ route('landlord.enquiries') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800">✉️ Enquiries</a>
                            @elseif(Auth::user()->isWelfare())
                                <a href="{{ route('welfare.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800">📊 Dashboard</a>
                                <a href="{{ route('welfare.applications') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800">🛏️ Allocations</a>
                                <a href="{{ route('welfare.landlords.verifications') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800">🏢 Landlords</a>
                                <a href="{{ route('welfare.support') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800">🛟 Support</a>
                            @elseif(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800">📊 Dashboard</a>
                                <a href="{{ route('admin.users') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800">👥 Users</a>
                                <a href="{{ route('admin.landlords.verifications') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800">🏢 Verifications</a>
                                <a href="{{ route('admin.properties.pending') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800">🏘️ Properties</a>
                                <a href="{{ route('admin.announcements') }}" class="block px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-800">📣 Announcements</a>
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
            <p class="text-gray-400">&copy; {{ date('Y') }} UB-UniStay. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
