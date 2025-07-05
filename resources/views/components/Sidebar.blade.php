<div class="d-flex">
  <!-- Sidebar -->
  <nav id="sidebarMenu" class="bg-dark text-white vh-100 d-flex flex-column p-3" style="width: 250px;">
    <div class="text-center mb-3">
      <img src="https://winthinassociates.com/images/logo/WinThinLogo.png" alt="Win Consulting Limited Logo" class="img-fluid w-50 mb-2">
      <h5 class="d-none d-lg-block text-white">Win Consulting Limited</h5>
    </div>
    <hr class="border-light">
    <ul class="nav nav-pills flex-column mb-auto">
      <!-- Dashboard Link -->
      <li class="nav-item mb-1">
        <a href="#" class="nav-link text-white d-flex align-items-center gap-2" aria-current="page">
          <i class="bi bi-house-door-fill"></i>
          <span class="d-lg-inline d-none">Dashboard</span>
        </a>
      </li>
      <!-- Attendance Link -->
      <li class="nav-item mb-1">
        <a href="#" class="nav-link text-white d-flex align-items-center gap-2">
          <i class="bi bi-calendar-check"></i>
          <span class="d-lg-inline d-none">Attendance</span>
        </a>
      </li>
      <!-- Dynamic user detail link -->
      <li class="nav-item mb-1">
        <a href="{{ url('/user/' . auth()->id()) }}" class="nav-link text-white d-flex align-items-center gap-2">
          <i class="bi bi-person-fill"></i>
          <span class="d-lg-inline d-none">Detail</span>
        </a>
      </li>
      <!-- Timesheet Link -->
      <li class="nav-item mb-1">
        <a href="#" class="nav-link text-white d-flex align-items-center gap-2">
          <i class="bi bi-clock-fill"></i>
          <span class="d-lg-inline d-none">Timesheet</span>
        </a>
      </li>
    </ul>
    <div class="mt-auto">
      <a href="#" class="btn btn-outline-light w-100">Logout</a>
    </div>
  </nav>

  <!-- Main Content Area -->
  <main class="flex-grow-1 p-3">
    @yield('content')
  </main>
</div>