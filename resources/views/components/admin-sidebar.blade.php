@if(auth()->check() && auth()->user()->role === 'admin')
    @php
        $user = auth()->user();
        $unreadNotifications = $user->unreadNotifications;
        $expiredMembersBadgeCount = $unreadNotifications
            ->filter(fn ($notification) => ($notification->data['action_type'] ?? null) === 'expired_subscriptions')
            ->sum(fn ($notification) => (int) ($notification->data['expired_count'] ?? 0));
        $badgeCount = $expiredMembersBadgeCount > 0 ? $expiredMembersBadgeCount : $unreadNotifications->count();
    @endphp
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
            @if($badgeCount > 0)
                <span class="badge badge-danger ml-auto">{{ $badgeCount }}</span>
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
@endif
