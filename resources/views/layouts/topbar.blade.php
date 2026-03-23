@auth
<header class="h-16 bg-card border-b border-border flex items-center justify-between px-6 shadow-sm">
    <!-- Page Title -->
    <div class="flex items-center gap-4">
        <button id="sidebar-toggle" class="lg:hidden p-2 text-secondary hover:text-primary transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        <h1 class="text-2xl font-bold text-main">@yield('page-title', 'Tableau de bord')</h1>
    </div>
    
    <!-- Actions -->
    <div class="flex items-center gap-3">
        <!-- Notifications -->
        <button class="relative p-2 text-secondary hover:text-primary transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span class="absolute top-1 right-1 w-2 h-2 bg-error rounded-full"></span>
        </button>
        
        <!-- User Menu -->
        <div class="relative">
            <button id="user-menu-toggle" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-surface transition-colors">
                <div class="w-8 h-8 bg-accent rounded-full flex items-center justify-center">
                    <span class="text-white text-sm font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
                <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            
            <!-- Dropdown Menu -->
            <div id="user-menu" class="absolute right-0 mt-2 w-48 bg-card border border-border rounded-lg shadow-lg hidden">
                <div class="p-3 border-b border-border">
                    <p class="text-sm font-medium text-main">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-secondary">{{ Auth::user()->email }}</p>
                </div>
                <div class="py-2">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 text-sm text-secondary hover:text-primary hover:bg-surface transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Profil
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-secondary hover:text-primary hover:bg-surface transition-colors text-left">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // User menu toggle
    const userMenuToggle = document.getElementById('user-menu-toggle');
    const userMenu = document.getElementById('user-menu');
    
    if (userMenuToggle && userMenu) {
        userMenuToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            userMenu.classList.toggle('hidden');
        });
        
        document.addEventListener('click', function() {
            userMenu.classList.add('hidden');
        });
        
        userMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
});
</script>
@endauth
