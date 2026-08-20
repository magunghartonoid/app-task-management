{{-- Sidenav --}}
<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <a class="nav-link {{ request()->routeIs('dashboard') ? '' : 'collapsed' }}" href="{{ route('dashboard') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard
                
            <a class="nav-link {{ request()->routeIs('users.*') ? '' : 'collapsed' }}" href="{{ route('users.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                Users

            <a class="nav-link {{ request()->routeIs('clients.*') ? '' : 'collapsed' }}" href="{{ route('clients.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-address-book"></i></div>
                Klien

            <a class="nav-link {{ request()->routeIs('requests.*') ? '' : 'collapsed' }}" href="{{ route('requests.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                Request
            </a>    
            </a>

            <a class="nav-link {{ request()->routeIs('reports.*') ? '' : 'collapsed' }}" href="{{ route('reports.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-file-export"></i></div>
                Report
            </a>
            </a>
            </a>
            </a>
        </div>
    </div>
    <div class="sb-sidenav-footer">
        <div class="small">Logged in as:</div>
        {{ Auth::check() ? Auth::user()->name : 'Guest' }}
    </div>
</nav>
{{-- End of Sidenav --}}
