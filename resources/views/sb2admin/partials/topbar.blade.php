{{-- Topnav --}}
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">

    {{-- Navbar Brand--}}
    <a class="navbar-brand ps-3" href="{{ route('dashboard') }}">TASK MANAGEMENT</a>

    {{-- Sidebar Toggle--}}
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
        <i class="fas fa-bars"></i>
    </button>

    {{-- Navbar - didorong penuh ke kanan --}}
    <ul class="navbar-nav ms-auto me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                <span class="me-2 d-none d-lg-inline small">
                    {{ Auth::check() ? Auth::user()->name : 'Guest' }}
                </span>
                @if (Auth::check())
                    <img src="{{ Auth::user()->photo_url }}" alt="{{ Auth::user()->name }}"
                        class="rounded-circle" style="width:32px;height:32px;object-fit:cover;"
                @else
                <i class="fas fa-user-circle fa-fw"></i>
                @endif
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                        Logout
                    </a>
                </li>
            </ul>
        </li>
    </ul>

</nav>
{{-- End of Topnav --}}
