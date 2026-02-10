<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Laravel'))</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        
        <!-- Alpine.js for interactive components -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <!-- jQuery and DataTables for admin tables -->
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        
        <!-- Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <!-- Bootstrap CSS for admin views -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
        
        <style>
            :root {
                --gym-primary: #FF6B35;
                --gym-secondary: #004E89;
                --gym-dark: #1A1A2E;
                --gym-accent: #F7931E;
                --gym-light: #FFE5D9;
                --gym-success: #10B981;
                --gym-warning: #F59E0B;
            }
            * { font-family: 'Figtree', sans-serif; }
            body { background: linear-gradient(135deg, var(--gym-dark) 0%, var(--gym-secondary) 100%); min-height: 100vh; }
            .card { background: white; border-radius: 1rem; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15); transition: transform 0.3s, box-shadow 0.3s; }
            .card:hover { transform: translateY(-2px); box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2); }
            .btn-primary { background: var(--gym-primary); transition: all 0.3s; border: none; cursor: pointer; }
            .btn-primary:hover { background: #ff8555; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(255, 107, 53, 0.4); }
            .sidebar { background: linear-gradient(180deg, var(--gym-dark) 0%, var(--gym-secondary) 100%); box-shadow: 4px 0 15px rgba(0, 0, 0, 0.2); }
            .sidebar a { transition: all 0.3s; border-radius: 0.5rem; display: block; }
            .sidebar a:hover { background: rgba(255, 107, 53, 0.2); transform: translateX(5px); }
            .sidebar button { transition: all 0.3s; border-radius: 0.5rem; width: 100%; text-align: left; background: none; border: none; color: inherit; cursor: pointer; }
            .sidebar button:hover { background: rgba(255, 107, 53, 0.2); transform: translateX(5px); }
            input, select, textarea { transition: all 0.3s; }
            input:focus, select:focus, textarea:focus { border-color: var(--gym-primary) !important; box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1) !important; outline: none; }
            .stat-card { background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); border-left: 4px solid var(--gym-primary); }
            .badge-success { background: rgba(16, 185, 129, 0.1); color: var(--gym-success); padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
            .badge-warning { background: rgba(245, 158, 11, 0.1); color: var(--gym-warning); padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
            .badge-primary { background: rgba(255, 107, 53, 0.1); color: var(--gym-primary); padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
            .pagination { display: flex; justify-content: center; align-items: center; gap: 0.5rem; margin-top: 1.5rem; }
            .pagination a, .pagination span { padding: 0.5rem 1rem; border-radius: 0.5rem; text-decoration: none; transition: all 0.3s; }
            .pagination a { background: white; color: var(--gym-primary); border: 2px solid var(--gym-primary); }
            .pagination a:hover { background: var(--gym-primary); color: white; }
            .pagination span { background: var(--gym-primary); color: white; border: 2px solid var(--gym-primary); }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen">
            @if(View::hasSection('sidebar'))
                <div class="flex">
                    <!-- Sidebar -->
                    <aside class="sidebar w-64 text-white min-h-screen p-4 fixed left-0 top-0 bottom-0">
                        <div class="mb-8">
                            <h1 class="text-2xl font-bold" style="color: var(--gym-primary);">GYM MANAGER</h1>
                            <p class="text-sm text-gray-300 mt-1">Système de gestion</p>
                        </div>
                        <div class="space-y-2">
                            @yield('sidebar')
                        </div>
                    </aside>
                    
                    <!-- Main Content -->
                    <main class="flex-1 p-8 ml-64">
                        @yield('content')
                    </main>
                </div>
            @else
                <!-- Navigation corrigée sans composants Blade -->
                <div x-data="{ open: false, notificationsOpen: false }" class="min-h-screen bg-gray-100">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between h-16">
                            <div class="flex">
                                <!-- Logo -->
                                <div class="shrink-0 flex items-center">
                                    <a href="{{ route('dashboard') }}">
                                        <span class="text-xl font-bold text-orange-500">GYM MANAGER</span>
                                    </a>
                                </div>
                                <!-- Navigation Links -->
                                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'text-orange-500' : 'text-gray-700' }} px-3 py-2 rounded-md text-sm font-medium hover:text-orange-600">Dashboard</a>
                                </div>
                            </div>

                            <!-- Notifications Dropdown (Admin only) -->
                            @auth
                                @if(Auth::user()->role === 'admin')
                                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                                        <div class="relative">
                                            <button @click="notificationsOpen = !notificationsOpen" class="relative inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                <i class="fas fa-bell"></i>
                                                @if(Auth::user()->unreadNotifications->count() > 0)
                                                    <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                                        {{ Auth::user()->unreadNotifications->count() }}
                                                    </span>
                                                @endif
                                            </button>

                                            <div x-show="notificationsOpen" @click.away="notificationsOpen = false" class="absolute right-0 mt-2 w-80 bg-white border rounded-md shadow-lg py-1 z-50 max-h-96 overflow-y-auto">
                                                <div class="px-4 py-2 border-b border-gray-200">
                                                    <h3 class="text-sm font-medium text-gray-900">Notifications</h3>
                                                </div>
                                                @if(Auth::user()->notifications->count() > 0)
                                                    @foreach(Auth::user()->notifications()->latest()->take(10)->get() as $notification)
                                                        <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 {{ $notification->read_at ? 'bg-gray-50' : '' }}" onclick="markAsRead({{ $notification->id }})">
                                                            <div class="flex items-start">
                                                                <div class="flex-shrink-0">
                                                                    @if($notification->data['action_type'] === 'new_member')
                                                                        <i class="fas fa-user-plus text-blue-500 mt-1"></i>
                                                                    @elseif($notification->data['action_type'] === 'payment')
                                                                        <i class="fas fa-credit-card text-green-500 mt-1"></i>
                                                                    @elseif($notification->data['action_type'] === 'new_course')
                                                                        <i class="fas fa-dumbbell text-orange-500 mt-1"></i>
                                                                    @else
                                                                        <i class="fas fa-info-circle text-gray-500 mt-1"></i>
                                                                    @endif
                                                                </div>
                                                                <div class="ml-3 flex-1">
                                                                    <p class="text-sm text-gray-900 {{ $notification->read_at ? 'font-normal' : 'font-semibold' }}">
                                                                        {{ $notification->data['title'] }}
                                                                    </p>
                                                                    <p class="text-xs text-gray-500 mt-1">
                                                                        {{ $notification->data['message'] }}
                                                                    </p>
                                                                    <p class="text-xs text-gray-400 mt-1">
                                                                        {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                                                    </p>
                                                                </div>
                                                                @if(!$notification->read_at)
                                                                    <div class="flex-shrink-0">
                                                                        <span class="inline-block w-2 h-2 bg-blue-600 rounded-full"></span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </a>
                                                    @endforeach
                                                    <div class="px-4 py-2 border-t border-gray-200">
                                                        <a href="{{ route('admin.notifications.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Voir toutes les notifications</a>
                                                    </div>
                                                @else
                                                    <div class="px-4 py-3 text-sm text-gray-500">
                                                        Aucune notification
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endauth

                            <!-- Settings Dropdown -->
                            <div class="hidden sm:flex sm:items-center sm:ms-6">
                                <div class="relative">
                                    <button @click="open = !open" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>{{ Auth::user()->name }}</div>
                                        <svg class="fill-current h-4 w-4 ms-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>

                                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white border rounded-md shadow-lg py-1 z-50">
                                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log Out</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Hamburger -->
                            <div class="-me-2 flex items-center sm:hidden">
                                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Responsive Menu -->
                    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
                        <div class="pt-2 pb-3 space-y-1">
                            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'text-orange-500' : 'text-gray-700' }} block px-3 py-2 rounded-md text-base font-medium hover:text-orange-600">Dashboard</a>
                        </div>

                        <div class="pt-4 pb-1 border-t border-gray-200">
                            <div class="px-4">
                                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                            </div>

                            <div class="mt-3 space-y-1">
                                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Profile</a>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Log Out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main>
                    @yield('content')
                </main>
            @endif
        </div>
    </body>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Notifications JavaScript -->
    <script>
        function markAsRead(notificationId) {
            fetch(`/admin/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the notification from the dropdown
                    const notificationElement = document.querySelector(`[onclick="markAsRead(${notificationId})"]`);
                    if (notificationElement) {
                        notificationElement.remove();
                    }
                    
                    // Update the unread count
                    const countElement = document.querySelector('.bg-red-600');
                    if (countElement) {
                        const currentCount = parseInt(countElement.textContent);
                        if (currentCount > 1) {
                            countElement.textContent = currentCount - 1;
                        } else {
                            countElement.remove();
                        }
                    }
                }
            })
            .catch(error => console.error('Error marking notification as read:', error));
        }
    </script>
    
    @stack('scripts')
</html>
