<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - MyFitness</title>
    
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
            --gym-bg-primary: #1A1A2E;
            --gym-bg-secondary: #004E89;
            --gym-primary: #FF6B35;
            --gym-text: #FFFFFF;
            --gym-secondary-bg: #F3F4F6;
            --gym-success: #10B981;
            --gym-warning: #F59E0B;
            --gym-danger: #EF4444;
            --gym-info: #3B82F6;
        }
        
        * { 
            font-family: 'Figtree', sans-serif; 
            box-sizing: border-box;
        }
        
        body { 
            background: linear-gradient(135deg, var(--gym-bg-primary) 0%, var(--gym-bg-secondary) 100%); 
            min-height: 100vh; 
            color: var(--gym-text);
        }
        
        .card { 
            background: white; 
            border-radius: 1rem; 
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15); 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .card:hover { 
            transform: translateY(-4px); 
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2); 
        }
        
        /* Buttons */
        .btn { 
            padding: 0.625rem 1.25rem; 
            border-radius: 0.5rem; 
            font-weight: 600; 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn:hover::before {
            left: 100%;
        }
        
        .btn-primary { 
            background: var(--gym-primary); 
            color: #FFFFFF;
        }
        
        .btn-primary:hover { 
            background: #ff8555; 
            transform: translateY(-2px); 
            box-shadow: 0 10px 25px rgba(255, 107, 53, 0.4); 
        }
        
        .btn-success { 
            background: var(--gym-success); 
            color: white;
        }
        
        .btn-success:hover { 
            background: #059669; 
            transform: translateY(-2px); 
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4); 
        }
        
        .btn-danger { 
            background: var(--gym-danger); 
            color: white;
        }
        
        .btn-danger:hover { 
            background: #DC2626; 
            transform: translateY(-2px); 
            box-shadow: 0 10px 25px rgba(239, 68, 68, 0.4); 
        }
        
        .btn-secondary { 
            background: #6B7280; 
            color: white;
        }
        
        .btn-secondary:hover { 
            background: #4B5563; 
            transform: translateY(-2px); 
        }
        
        .btn-info { 
            background: var(--gym-info); 
            color: white;
        }
        
        .btn-info:hover { 
            background: #2563EB; 
            transform: translateY(-2px); 
        }
        
        /* Sidebar */
        .sidebar { 
            background: linear-gradient(180deg, var(--gym-bg-primary) 0%, var(--gym-bg-secondary) 100%); 
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
        }
        
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 107, 53, 0.5);
            border-radius: 3px;
        }
        
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 107, 53, 0.7);
        }
        
        .sidebar .sticky {
            backdrop-filter: blur(10px);
            background: linear-gradient(180deg, rgba(26, 26, 46, 0.95) 0%, rgba(0, 78, 137, 0.95) 100%);
        }
        
        .sidebar a { 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            border-radius: 0.75rem; 
            display: flex;
            align-items: center;
            padding: 0.875rem 1rem;
            margin-bottom: 0.5rem;
            color: var(--gym-text);
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }
        
        .sidebar a::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 107, 53, 0.1), transparent);
            transition: left 0.5s;
        }
        
        .sidebar a:hover::before {
            left: 100%;
        }
        
        .sidebar a:hover { 
            background: rgba(255, 107, 53, 0.15); 
            transform: translateX(8px);
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.2);
        }
        
        .sidebar a.active {
            background: rgba(255, 107, 53, 0.2);
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
        }
        
        .sidebar button { 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            border-radius: 0.75rem; 
            width: 100%; 
            text-align: left; 
            background: none; 
            border: none; 
            color: var(--gym-text); 
            cursor: pointer;
            padding: 0.875rem 1rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        
        .sidebar button:hover { 
            background: rgba(255, 107, 53, 0.15); 
            transform: translateX(8px);
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.2);
        }
        
        /* Alerts */
        .alert { 
            padding: 1rem 1.25rem; 
            border-radius: 0.75rem; 
            border-left: 4px solid; 
            margin-bottom: 1.5rem;
            font-weight: 500;
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .alert-success { 
            background: linear-gradient(135deg, #D1FAE5 0%, #A7F3D0 100%); 
            border-left-color: var(--gym-success); 
            color: #065F46;
        }
        
        .alert-danger { 
            background: linear-gradient(135deg, #FEE2E2 0%, #FCA5A5 100%); 
            border-left-color: var(--gym-danger); 
            color: #991B1B;
        }
        
        .alert-warning { 
            background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%); 
            border-left-color: var(--gym-warning); 
            color: #92400E;
        }
        
        .alert-info { 
            background: linear-gradient(135deg, #DBEAFE 0%, #BFDBFE 100%); 
            border-left-color: var(--gym-info); 
            color: #1E40AF;
        }
        
        /* Badges */
        .badge { 
            padding: 0.375rem 0.875rem; 
            border-radius: 9999px; 
            font-weight: 600; 
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }
        
        .badge-success { 
            background: rgba(16, 185, 129, 0.1); 
            color: var(--gym-success);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }
        
        .badge-warning { 
            background: rgba(245, 158, 11, 0.1); 
            color: var(--gym-warning);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }
        
        .badge-primary { 
            background: rgba(255, 107, 53, 0.1); 
            color: var(--gym-primary);
            border: 1px solid rgba(255, 107, 53, 0.2);
        }
        
        .badge-info { 
            background: rgba(59, 130, 246, 0.1); 
            color: var(--gym-info);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }
        
        .badge-danger { 
            background: rgba(239, 68, 68, 0.1); 
            color: var(--gym-danger);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        
        /* Tables */
        .table { 
            background: white; 
            border-radius: 0.75rem; 
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--gym-secondary-bg);
        }
        
        .table thead th { 
            background: linear-gradient(135deg, var(--gym-bg-primary) 0%, var(--gym-bg-secondary) 100%); 
            color: var(--gym-text); 
            border: none; 
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.875rem;
            letter-spacing: 0.025em;
            padding: 1rem;
        }
        
        .table tbody tr { 
            transition: all 0.2s ease;
            border-bottom: 1px solid var(--gym-secondary-bg);
        }
        
        .table tbody tr:hover { 
            background: linear-gradient(90deg, rgba(255, 107, 53, 0.05) 0%, rgba(255, 107, 53, 0.02) 100%);
            transform: scale(1.01);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .table tbody tr:nth-child(even) {
            background: var(--gym-secondary-bg);
        }
        
        .table tbody tr:nth-child(even):hover {
            background: linear-gradient(90deg, rgba(255, 107, 53, 0.08) 0%, rgba(255, 107, 53, 0.04) 100%);
        }
        
        /* Forms */
        .form-control, .form-select {
            border: 2px solid var(--gym-secondary-bg);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-control:focus, .form-select:focus { 
            border-color: var(--gym-primary) !important; 
            box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1) !important; 
            outline: none;
        }
        
        /* Pagination */
        .pagination { 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            gap: 0.5rem; 
            margin-top: 2rem;
        }
        
        .pagination a, .pagination span { 
            padding: 0.625rem 1rem; 
            border-radius: 0.5rem; 
            text-decoration: none; 
            transition: all 0.3s ease;
            font-weight: 500;
            border: 2px solid transparent;
        }
        
        .pagination a:hover {
            background: var(--gym-primary);
            color: #FFFFFF;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .card {
                margin: 1rem;
            }
            
            .table {
                font-size: 0.875rem;
            }
            
            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }
        }
        
        /* Main content margin for desktop */
        @media (min-width: 769px) {
            .main-content {
                margin-left: 16rem; /* 256px = 16rem */
            }
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="sidebar w-64 min-h-screen flex flex-col">
            <!-- Fixed Header -->
            <div class="p-6 pb-4 sticky top-0 bg-gradient-to-b from-gym-bg-primary to-gym-bg-secondary z-10">
                <h1 class="text-2xl font-bold text-white mb-2 flex items-center gap-2">
                    <i class="fas fa-dumbbell"></i>
                    <span>MyFitness</span>
                </h1>
                <p class="text-gray-300 text-sm">Panel d'Administration</p>
            </div>
            
            <!-- Scrollable Navigation -->
            <div class="flex-1 px-6 pb-6 overflow-y-auto">
                @if(auth()->check() && auth()->user()->role === 'admin')
                    @section('sidebar')
                        <nav class="space-y-2">
                            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt me-3"></i> 
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('admin.members.index') }}" class="{{ request()->routeIs('admin.members.*') ? 'active' : '' }}">
                                <i class="fas fa-user me-3"></i> 
                                <span>Membres</span>
                            </a>
                            <a href="{{ route('admin.coaches.index') }}" class="{{ request()->routeIs('admin.coaches.*') ? 'active' : '' }}">
                                <i class="fas fa-user-tie me-3"></i> 
                                <span>Coachs</span>
                            </a>
                            <a href="{{ route('admin.receptionists.index') }}" class="{{ request()->routeIs('admin.receptionists.*') ? 'active' : '' }}">
                                <i class="fas fa-clipboard me-3"></i> 
                                <span>Receptionnistes</span>
                            </a>
                            <a href="{{ route('admin.subscription-types.index') }}" class="{{ request()->routeIs('admin.subscription-types.*') ? 'active' : '' }}">
                                <i class="fas fa-credit-card me-3"></i> 
                                <span>Types d'Abonnement</span>
                            </a>
                            <a href="{{ route('admin.classes.index') }}" class="{{ request()->routeIs('admin.classes.*') ? 'active' : '' }}">
                                <i class="fas fa-dumbbell me-3"></i> 
                                <span>Gestion de cours</span>
                            </a>
                            <a href="{{ route('admin.classes.pending') }}" class="{{ request()->routeIs('admin.classes.pending') ? 'active' : '' }}">
                                <i class="fas fa-check-circle me-3"></i> 
                                <span>Validation des Cours</span>
                            </a>
                            <a href="{{ route('admin.payments.index') }}" class="{{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                                <i class="fas fa-money-bill-wave me-3"></i> 
                                <span>Paiements</span>
                            </a>
                            <a href="{{ route('admin.notifications.index') }}" class="{{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                                <i class="fas fa-bell me-3"></i> 
                                <span>Notifications</span>
                                @php
                                    $unreadNotifications = auth()->user()->unreadNotifications;
                                    $expiredMembersBadgeCount = $unreadNotifications
                                        ->filter(fn ($notification) => ($notification->data['action_type'] ?? null) === 'expired_subscriptions')
                                        ->sum(fn ($notification) => (int) ($notification->data['expired_count'] ?? 0));
                                    $badgeCount = $expiredMembersBadgeCount > 0 ? $expiredMembersBadgeCount : $unreadNotifications->count();
                                @endphp
                                @if($badgeCount > 0)
                                    <span class="badge badge-danger ml-auto">
                                        {{ $badgeCount }}
                                    </span>
                                @endif
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-red-300 hover:text-red-200 w-full text-left flex items-center gap-3">
                                    <i class="fas fa-sign-out-alt"></i> 
                                    <span>Deconnexion</span>
                                </button>
                            </form>
                        </nav>
                    @show
                @endif
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto main-content">
            <div class="p-8">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-3"></i>
                            {{ session('success') }}
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle me-3"></i>
                            {{ session('error') }}
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle me-3"></i>
                            {{ session('warning') }}
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle me-3"></i>
                            {{ session('info') }}
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS for interactions -->
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                if (alert.classList.contains('alert-success')) {
                    alert.style.transition = 'opacity 0.5s, transform 0.5s';
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateX(-100%)';
                    setTimeout(() => alert.remove(), 500);
                }
            });
        }, 5000);
        
        // Enhanced table interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effects to table rows
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.cursor = 'pointer';
                });
            });
            
            // Initialize DataTables with enhanced styling
            if (window.jQuery && window.jQuery.fn.dataTable) {
                jQuery('.datatable').DataTable({
                    language: {
                        search: "Rechercher:",
                        lengthMenu: "Afficher _MENU_ entrees",
                        info: "Affichage de _START_ a _END_ sur _TOTAL_ entrees",
                        paginate: {
                            first: "Premier",
                            last: "Dernier",
                            next: "Suivant",
                            previous: "Precedent"
                        }
                    },
                    pageLength: 10,
                    responsive: true
                });
            }
        });
    </script>
</body>
</html>


