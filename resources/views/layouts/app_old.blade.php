<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>

        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" content="{{ csrf_token() }}">



        <title>@yield('title', config('app.name', 'Laravel'))</title>



        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

        

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

        

        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    </head>

    <body class="font-sans antialiased bg-background">

        <div class="min-h-screen">

            @if(View::hasSection('sidebar'))

                <div class="flex">

                    <!-- Sidebar -->
                    <aside class="sidebar w-64 text-text-primary min-h-screen p-6 fixed left-0 top-0 bottom-0">
                        <div class="mb-8">
                            <h1 class="text-2xl font-bold text-primary">MyFitness</h1>
                            <p class="text-sm text-secondary mt-1">Système de gestion</p>
                        </div>
                        <div class="space-y-2">
                            @yield('sidebar')
                        </div>
                    </aside>

                    

                    <!-- Main Content -->
                    <main class="flex-1 p-8 ml-64 bg-background min-h-screen {{ request()->routeIs('receptionist.members.*') || request()->routeIs('receptionist.payments.*') ? 'receptionist-content' : '' }}">
                        @yield('content')
                    </main>

                </div>

            @else

                <!-- Navigation -->
                <nav x-data="{ open: false }" class="bg-surface border-b border-border">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between h-16">
                            <div class="flex">
                                <!-- Logo -->
                                <div class="shrink-0 flex items-center">
                                    <a href="{{ route('dashboard') }}" class="navbar-brand">
                                        MyFitness
                                    </a>
                                </div>
                                <!-- Navigation Links -->
                                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                        Tableau de bord
                                    </a>
                                </div>
                            </div>



                            <!-- Settings Dropdown -->
                            <div class="hidden sm:flex sm:items-center sm:ms-6">
                                <div class="relative">
                                    <button @click="open = !open" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-text-secondary bg-surface hover:text-text-primary focus:outline-none transition ease-in-out duration-150">
                                        <div>{{ Auth::user()->name }}</div>
                                        <svg class="fill-current h-4 w-4 ms-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-surface border border-border rounded-md shadow-lg py-1 z-50">
                                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-text-primary hover:bg-background">Profil</a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-text-primary hover:bg-background">Déconnexion</button>
                                        </form>
                                    </div>
                                </div>
                            </div>



                            <!-- Hamburger -->
                            <div class="-me-2 flex items-center sm:hidden">
                                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-text-secondary hover:text-text-primary hover:bg-background focus:outline-none focus:bg-background focus:text-text-primary transition duration-150 ease-in-out">
                                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                        </div>

                    </div>



                    <!-- Responsive Menu -->
                    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-surface border-t border-border">
                        <div class="pt-2 pb-3 space-y-1">
                            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'text-primary bg-background' : 'text-text-secondary' }} block px-3 py-2 rounded-md text-base font-medium hover:text-primary hover:bg-background transition-colors">Tableau de bord</a>
                        </div>
                        <div class="pt-4 pb-1 border-t border-border">
                            <div class="px-4">
                                <div class="font-medium text-base text-text-primary">{{ Auth::user()->name }}</div>
                                <div class="font-medium text-sm text-text-secondary">{{ Auth::user()->email }}</div>
                            </div>
                            <div class="mt-3 space-y-1">
                                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-md text-base font-medium text-text-primary hover:bg-background transition-colors">Profil</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left block px-3 py-2 rounded-md text-base font-medium text-text-primary hover:bg-background transition-colors">Déconnexion</button>
                                </form>
                            </div>
                        </div>
                    </div>

                </nav>



                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-surface border-b border-border shadow-sm">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset



                <!-- Page Content -->
                <main class="bg-background min-h-screen">
                    @yield('content')
                </main>

            @endif

        </div>

    </body>

    

    <!-- Bootstrap JS -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    

    @stack('scripts')

    <style>
        /* Suppression des anciens styles - maintenant dans app.css */
    </style>



