<div class="d-flex">
  <!-- Sidebar -->
  <nav id="sidebarMenu" class="bg-white text-dark d-flex flex-column p-3 sticky-sidebar" style="width: 250px;">
    <div class="text-center mb-3">
      <img src="{{ asset('Logo/logo.png')}}"  class="w-50" alt="">
      <h5 class="d-none d-lg-block text-dark">Win Consulting Limited</h5>
    </div>
    <hr class="border-light">
    <ul class="nav nav-pills flex-column mb-auto">
      <!-- Dashboard Link -->
      <!-- <li class="nav-item mb-1">
        <a href="{{ route('user.dashboard') }}" class="nav-link text-dark d-flex align-items-center gap-2 {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" aria-current="page">
          <i class="bi bi-house-door-fill"></i>
          <span class="d-lg-inline d-none">Dashboard</span>
        </a>
      </li> -->
      <!-- Attendance Link -->
      <li class="nav-item mb-1">
        <a href="{{ route('user.attendance') }}" class="nav-link text-dark d-flex align-items-center gap-2 {{ request()->routeIs('user.attendance') ? 'active' : '' }}">
          <i class="bi bi-calendar-check"></i> <!-- Already fitting, represents attendance -->
          <span class="d-lg-inline d-none">Attendance</span>
        </a>
      </li>
      <li class="nav-item mb-1 d-none d-lg-block">
        <a href="{{ route('user.attendances') }}" class="nav-link text-dark d-flex align-items-center gap-2 {{ request()->routeIs('user.attendances') ? 'active' : '' }}">
          <i class="bi bi-calendar-event"></i> <!-- Changed to represent history of events -->
          <span class="d-lg-inline d-none">Attendances History</span>
        </a>
      </li>
      <li class="nav-item mb-1">
        <a href="{{ route('user.detail') }}" class="nav-link text-dark d-flex align-items-center gap-2 {{ request()->routeIs('user.detail') ? 'active' : '' }}">
          <i class="bi bi-person-fill"></i> <!-- Already fitting, represents user profile -->
          <span class="d-lg-inline d-none">Detail</span>
        </a>
      </li>
      <li class="nav-item mb-1 d-none d-lg-block">
        <a href="{{ route('user.timesheet') }}" class="nav-link text-dark d-flex align-items-center gap-2 {{ request()->routeIs('user.timesheet*') ? 'active' : '' }}">
          <i class="bi bi-clock-fill"></i> <!-- Already fitting, represents time tracking -->
          <span class="d-lg-inline d-none">Timesheet</span>
        </a>
      </li>
      @if(auth()->user()->level > 1)
        <li class="nav-item mb-1 d-none d-lg-block">
          <a href="{{ route('admin.clients') }}" class="nav-link text-dark d-flex align-items-center gap-2 {{ request()->routeIs('admin.clients*') ? 'active' : '' }}">
            <i class="fa-solid fa-building"></i> <!-- Already fitting, represents clients/business -->
            <span class="d-lg-inline d-none">Clients</span>
          </a>
        </li>
      @endif
      @if(auth()->user()->level == 3)
        <li class="nav-item mb-1">
          <a href="{{ route('admin.teams') }}" class="nav-link text-dark d-flex align-items-center gap-2 {{ request()->routeIs('admin.teams*') ? 'active' : '' }}">
            <i class="fa-solid fa-users"></i> <!-- Changed to fa-solid for consistency with other Font Awesome icons -->
            <span class="d-lg-inline d-none">Teams</span>
          </a>
        </li>
        <li class="nav-item mb-1">
          <a href="{{ route('admin.users') }}" class="nav-link text-dark d-flex align-items-center gap-2 {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <i class="fa-solid fa-user-group"></i> <!-- Changed to differentiate from Teams, represents user management -->
            <span class="d-lg-inline d-none">Users</span>
          </a>
        </li>
      @endif
    </ul>
    <div class="mt-auto">
      <h5 class="">{{ auth()->user()->name }}</h5>
      @if (auth()->user()->level == 1)
        <p>User</p>
      @elseif (auth()->user()->level == 2)
        <p>Team Leader</p>
      @else 
        <p>Admin</p>
      @endif
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-blue w-100">
          <i class="bi bi-box-arrow-right me-2"></i>
          <span class="text-white d-none d-sm-inline ">Logout</span>
        </button>
      </form>
    </div>
  </nav>

  <!-- Main Content Area -->
  <main class="flex-grow-1 main-content">
    @yield('content')
  </main>
</div>

<style>
  .btn-blue {
    background-color: rgba(134, 0, 0, 1);
  }
  .btn-blue:hover {
    background-color: rgba(39, 0, 132, 1);
    color: black;
  }
  .sticky-sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    overflow-y: auto;
    z-index: 1000;
  }

  .main-content {
    margin-left: 250px;
    padding: 20px;
    min-height: 100vh;
  }

  /* Active state for navigation links */
  .nav-link.active {
    background-color: rgba(0, 57, 128, 1) !important;
    color: #fff !important;
  }

  .nav-link:hover {
    background-color: rgba(0, 57, 128, 1) !important;
    color: #fff !important;
  }

  /* Responsive adjustments */
  @media (max-width: 991.98px) {
    .sticky-sidebar {
      width: 80px !important;
    }
    
    .main-content {
      margin-left: 80px;
    }
    
    .sticky-sidebar .nav-link span {
      display: none !important;
    }
    
    .sticky-sidebar h5 {
      display: none !important;
    }
  }

  @media (max-width: 767.98px) {
    .sticky-sidebar {
      width: 60px !important;
    }
    
    .main-content {
      margin-left: 60px;
      padding: 15px;
    }
  }

  /* Custom scrollbar for sidebar */
  .sticky-sidebar::-webkit-scrollbar {
    width: 6px;
  }

  .sticky-sidebar::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
  }

  .sticky-sidebar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.3);
    border-radius: 3px;
  }

  .sticky-sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.5);
  }
</style>
