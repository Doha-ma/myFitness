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
            
            * {
                font-family: 'Figtree', sans-serif;
            }
            
            body {
                background: linear-gradient(135deg, var(--gym-dark) 0%, var(--gym-secondary) 100%);
                min-height: 100vh;
            }
            
            .card {
                background: white;
                border-radius: 1rem;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
                transition: transform 0.3s, box-shadow 0.3s;
            }
            
            .card:hover {
                transform: translateY(-2px);
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            }
            
            .btn-primary {
                background: var(--gym-primary);
                transition: all 0.3s;
                border: none;
                cursor: pointer;
            }
            
            .btn-primary:hover {
                background: #ff8555;
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(255, 107, 53, 0.4);
            }
            
            .sidebar {
                background: linear-gradient(180deg, var(--gym-dark) 0%, var(--gym-secondary) 100%);
                box-shadow: 4px 0 15px rgba(0, 0, 0, 0.2);
            }
            
            .sidebar a {
                transition: all 0.3s;
                border-radius: 0.5rem;
                display: block;
            }
            
            .sidebar a:hover {
                background: rgba(255, 107, 53, 0.2);
                transform: translateX(5px);
            }
            
            .sidebar button {
                transition: all 0.3s;
                border-radius: 0.5rem;
                width: 100%;
                text-align: left;
                background: none;
                border: none;
                color: inherit;
                cursor: pointer;
            }
            
            .sidebar button:hover {
                background: rgba(255, 107, 53, 0.2);
                transform: translateX(5px);
            }
            
            input, select, textarea {
                transition: all 0.3s;
            }
            
            input:focus, select:focus, textarea:focus {
                border-color: var(--gym-primary) !important;
                box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1) !important;
                outline: none;
            }
            
            .stat-card {
                background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
                border-left: 4px solid var(--gym-primary);
            }
            
            .badge-success {
                background: rgba(16, 185, 129, 0.1);
                color: var(--gym-success);
                padding: 0.25rem 0.75rem;
                border-radius: 9999px;
                font-size: 0.75rem;
                font-weight: 600;
            }
            
            .badge-warning {
                background: rgba(245, 158, 11, 0.1);
                color: var(--gym-warning);
                padding: 0.25rem 0.75rem;
                border-radius: 9999px;
                font-size: 0.75rem;
                font-weight: 600;
            }
            
            .badge-primary {
                background: rgba(255, 107, 53, 0.1);
                color: var(--gym-primary);
                padding: 0.25rem 0.75rem;
                border-radius: 9999px;
                font-size: 0.75rem;
                font-weight: 600;
            }
            
            /* Pagination styles */
            .pagination {
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 0.5rem;
                margin-top: 1.5rem;
            }
            
            .pagination a, .pagination span {
                padding: 0.5rem 1rem;
                border-radius: 0.5rem;
                text-decoration: none;
                transition: all 0.3s;
            }
            
            .pagination a {
                background: white;
                color: var(--gym-primary);
                border: 2px solid var(--gym-primary);
            }
            
            .pagination a:hover {
                background: var(--gym-primary);
                color: white;
            }
            
            .pagination span {
                background: var(--gym-primary);
                color: white;
                border: 2px solid var(--gym-primary);
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen">
            @if(View::hasSection('sidebar'))
            <div class="flex">
                <!-- Sidebar -->
                <aside class="sidebar w-64 text-white min-h-screen p-4 fixed left-0 top-0 bottom-0">
                    <div class="mb-8">
                        <h1 class="text-2xl font-bold" style="color: var(--gym-primary);"> GYM MANAGER</h1>
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
                @include('layouts.navigation')
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
                    {{ $slot }}
                </main>
            @endif
        </div>
    </body>
</html>
