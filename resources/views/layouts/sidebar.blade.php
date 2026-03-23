@auth
@php
$userRole = auth()->user()->role ?? 'user';
$sidebarClass = '';
$accentColor = '';
switch($userRole) {
    case 'admin':
        $sidebarClass = 'sidebar-admin';
        $accentColor = 'var(--admin-accent)';
        break;
    case 'coach':
        $sidebarClass = 'sidebar-coach';
        $accentColor = 'var(--coach-accent)';
        break;
    case 'receptionniste':
        $sidebarClass = 'sidebar-recep';
        $accentColor = 'var(--recep-accent)';
        break;
    default:
        $sidebarClass = '';
        $accentColor = 'var(--accent)';
}
@endphp
<aside class="w-60 {{ $sidebarClass }} flex flex-col h-full">
    <!-- Logo -->
    <div class="h-16 flex items-center px-6 border-b border-gray-700">
        <h1 class="text-white text-xl font-bold">
            MyFitness
        </h1>
    </div>
    
    <!-- Navigation -->
    <nav class="flex-1 p-4 space-y-1">
        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            Tableau de bord
        </a>
        
        @if($userRole === 'admin')
            <div class="sidebar-section">ADMINISTRATION</div>
            
            <a href="{{ route('admin.coaches.index') }}" class="sidebar-link {{ request()->routeIs('admin.coaches.*') ? 'active' : '' }}">
                Coachs
            </a>
            
            <a href="{{ route('admin.classes.index') }}" class="sidebar-link {{ request()->routeIs('admin.classes.*') ? 'active' : '' }}">
                Cours
            </a>
            
            <a href="{{ route('admin.classes.pending') }}" class="sidebar-link {{ request()->routeIs('admin.classes.pending') ? 'active' : '' }}">
                Validation
            </a>
            
            <a href="{{ route('admin.members.index') }}" class="sidebar-link {{ request()->routeIs('admin.members.*') ? 'active' : '' }}">
                Membres
            </a>
            
            <a href="{{ route('admin.subscriptions.index') }}" class="sidebar-link {{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
                Abonnements
            </a>
            
            <a href="{{ route('admin.payments.index') }}" class="sidebar-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                Paiements
            </a>
        @endif
        
        @if($userRole === 'coach')
            <div class="sidebar-section">COACH</div>
            
            <a href="{{ route('coach.classes.index') }}" class="sidebar-link {{ request()->routeIs('coach.classes.*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Mes cours
            </a>
            
            <a href="{{ route('coach.schedule.index') }}" class="sidebar-link {{ request()->routeIs('coach.schedule.*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Planning
            </a>
            
            <a href="{{ route('coach.members.index') }}" class="sidebar-link {{ request()->routeIs('coach.members.*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Mes membres
            </a>
        @endif
        
        @if($userRole === 'receptionniste')
            <div class="sidebar-section">RÉCEPTION</div>
            
            <a href="{{ route('recep.checkin.index') }}" class="sidebar-link {{ request()->routeIs('recep.checkin.*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Check-in
            </a>
            
            <a href="{{ route('recep.members.index') }}" class="sidebar-link {{ request()->routeIs('recep.members.*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Membres
            </a>
            
            <a href="{{ route('recep.subscriptions.new') }}" class="sidebar-link {{ request()->routeIs('recep.subscriptions.*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Nouvel abonnement
            </a>
            
            <a href="{{ route('recep.payments.new') }}" class="sidebar-link {{ request()->routeIs('recep.payments.*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Encaisser
            </a>
        @endif
        
        <div class="sidebar-section">UTILISATEUR</div>
        
        <a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Profil
        </a>
    </nav>
    
    <!-- User Section -->
    <div class="p-4 border-t border-gray-700">
        <div class="flex items-center gap-3">
            <div class="avatar avatar-primary">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="flex-1">
                <p class="text-white text-sm font-medium">{{ Auth::user()->name }}</p>
                <p class="text-gray-300 text-xs">{{ ucfirst($userRole) }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-gray-300 hover:text-white hover:bg-white hover:bg-opacity-10 rounded-lg transition-all text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Déconnexion
            </button>
        </form>
    </div>
</aside>
@endauth
