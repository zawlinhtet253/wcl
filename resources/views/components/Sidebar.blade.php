
<div class="d-flex min-vh-100">
    <!-- Sidebar -->
    <nav id="sidebarMenu" class="bg-dark text-white d-flex flex-column p-3 sticky-top" style="width: 60px; transition: width 0.3s;" data-expanded="false">
        <div class="text-center mb-3">
            <img src="{{ asset('Logo/logo.png') }}" class="w-75" alt="Win Consulting Limited">
            <h5 class="mt-2 text-white d-none d-lg-block">Win Consulting Limited</h5>
        </div>
        <hr class="border-light">
        <ul class="nav nav-pills flex-column mb-auto">
            <!-- Attendance Link -->
            <li class="nav-item mb-1">
                <a href="{{ route('user.attendance') }}" class="nav-link text-white d-flex align-items-center gap-2 {{ request()->routeIs('user.attendance') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i>
                    <span class="d-md-inline d-none">Attendance</span>
                </a>
            </li>
            <!-- Attendances History Link -->
            <li class="nav-item mb-1 d-none d-lg-block">
                <a href="{{ route('user.attendances') }}" class="nav-link text-white d-flex align-items-center gap-2 {{ request()->routeIs('user.attendances') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i>
                    <span class="d-md-inline d-none">Attendances History</span>
                </a>
            </li>
            <!-- Dynamic user detail link -->
            <li class="nav-item mb-1">
                <a href="{{ route('user.detail') }}" class="nav-link text-white d-flex align-items-center gap-2 {{ request()->routeIs('user.detail') ? 'active' : '' }}">
                    <i class="bi bi-person-fill"></i>
                    <span class="d-md-inline d-none">Detail</span>
                </a>
            </li>
            <!-- Timesheet Link -->
            <li class="nav-item mb-1 d-none d-lg-block">
                <a href="{{ route('user.timesheet') }}" class="nav-link text-white d-flex align-items-center gap-2 {{ request()->routeIs('user.timesheet*') ? 'active' : '' }}">
                    <i class="bi bi-clock-fill"></i>
                    <span class="d-md-inline d-none">Timesheet</span>
                </a>
            </li>
            @if(auth()->user()->level == 3)
                <li class="nav-item mb-1 d-none d-md-block d-lg-block">
                    <a href="{{ route('admin.clients') }}" class="nav-link text-white d-flex align-items-center gap-2 {{ request()->routeIs('admin.clients*') ? 'active' : '' }}">
                        <i class="fa-solid fa-building"></i>
                        <span class="d-md-inline d-none">Clients</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="{{ route('admin.teams') }}" class="nav-link text-white d-flex align-items-center gap-2 {{ request()->routeIs('admin.teams*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span class="d-md-inline d-none">Teams</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="{{ route('admin.users') }}" class="nav-link text-white d-flex align-items-center gap-2 {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <i class="fa-solid fa-users"></i>
                        <span class="d-md-inline d-none">Users</span>
                    </a>
                </li>
            @endif
        </ul>
        <div class="mt-auto">
            <h5 class="d-none d-md-block">{{ auth()->user()->name }}</h5>
            @if (auth()->user()->level == 1)
                <p class="d-none d-md-block">User</p>
            @elseif (auth()->user()->level == 2)
                <p class="d-none d-md-block">Team Leader</p>
            @else
                <p class="d-none d-md-block">Admin</p>
            @endif
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-light w-100">
                    <i class="bi bi-box-arrow-right me-2"></i>
                    <span class="d-md-inline d-none">Logout</span>
                </button>
            </form>
        </div>
        <!-- Toggle Button for Mobile -->
        <button class="btn btn-outline-light mt-2 d-md-none" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>
    </nav>

    <!-- Main Content Area -->
    <main class="flex-grow-1" style="margin-left: 60px; padding: 15px; transition: margin-left 0.3s;">
        @yield('content')
    </main>
</div>

<style>
/* Active state for navigation links */
.nav-link.active {
    background-color: rgba(255, 255, 255, 0.2) !important;
    color: #fff !important;
    border-radius: 4px;
}

.nav-link:hover {
    background-color: rgba(255, 255, 255, 0.1);
    color: #fff;
}

/* Custom scrollbar for sidebar */
#sidebarMenu::-webkit-scrollbar {
    width: 6px;
}

#sidebarMenu::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
}

#sidebarMenu::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.3);
    border-radius: 3px;
}

#sidebarMenu::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.5);
}

/* Responsive adjustments */
@media (min-width: 768px) {
    #sidebarMenu {
        width: 250px !important;
    }
    #sidebarMenu[data-expanded="true"] {
        width: 250px !important;
    }
    main {
        margin-left: 250px !important;
    }
}
</style>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebarMenu');
    const main = document.querySelector('main');
    const isExpanded = sidebar.getAttribute('data-expanded') === 'true';
    
    if (isExpanded) {
        sidebar.style.width = '60px';
        sidebar.setAttribute('data-expanded', 'false');
        main.style.marginLeft = '60px';
        sidebar.querySelectorAll('.d-md-inline').forEach(el => el.classList.add('d-none'));
        sidebar.querySelectorAll('.d-none.d-md-block').forEach(el => el.classList.add('d-none'));
    } else {
        sidebar.style.width = '250px';
        sidebar.setAttribute('data-expanded', 'true');
        main.style.marginLeft = '250px';
        sidebar.querySelectorAll('.d-md-inline').forEach(el => el.classList.remove('d-none'));
        sidebar.querySelectorAll('.d-none.d-md-block').forEach(el => el.classList.remove('d-none'));
    }
}
</script>
