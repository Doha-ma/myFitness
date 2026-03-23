@auth
<nav class="navbar">
    <a href="{{ route('dashboard') }}" class="navbar-brand">
        MyFitness
    </a>
    
    <ul class="navbar-nav">
        @if(request()->routeIs('dashboard'))
            <li><a href="{{ route('dashboard') }}" class="active">Tableau de bord</a></li>
        @else
            <li><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
        @endif
        
        @guest
            <li><a href="{{ route('login') }}">Connexion</a></li>
        @else
            <li>
                <div style="position: relative;">
                    <button onclick="this.nextElementSibling.style.display = this.nextElementSibling.style.display === 'block' ? 'none' : 'block'" style="background: none; border: none; color: rgba(255,255,255,0.80); cursor: pointer;">
                        {{ Auth::user()->name }}
                    </button>
                    <div style="display: none; position: absolute; right: 0; top: 100%; background: white; border-radius: 8px; box-shadow: var(--shadow-md); min-width: 200px; z-index: 1000;">
                        <a href="{{ route('profile.edit') }}" style="display: block; padding: 8px 16px; color: var(--text-main); text-decoration: none;">Profil</a>
                        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                            @csrf
                            <button type="submit" style="display: block; width: 100%; text-align: left; padding: 8px 16px; background: none; border: none; color: var(--text-main); cursor: pointer;">Déconnexion</button>
                        </form>
                    </div>
                </div>
            </li>
        @endguest
    </ul>
</nav>
@endauth
