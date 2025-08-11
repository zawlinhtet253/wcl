@extends('layouts.app')
@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">User Profile</h1>
            <p class="text-muted mb-0">Manage user information and details</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Users
            </a>
            <a href="" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i> Edit Profile
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Left Column - Profile Overview -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <!-- Profile Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center p-4">
                    <!-- Profile Avatar -->
                    <div class="mb-3">
                        <div class="avatar-xl d-flex justify-content-center mb-3">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" 
                                     class="rounded-circle img-fluid" style="width: 120px; height: 120px; object-fit: cover;">
                            @else
                                <div class="bg-primary rounded-circle d-flex 
                                align-items-center justify-content-center text-white" 
                                     style="width: 120px; height: 120px; font-size: 48px; font-weight: 600;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Basic Info -->
                    <h4 class="mb-1">{{ $user->name }}</h4>
                    @if($employee)
                        <p class="text-muted mb-2">{{ $employee->position ?? 'Employee' }}</p>
                        <span class="badge bg-primary mb-3">{{ $employee->employee_code }}</span>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="mailto:{{ $user->email }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-envelope me-2"></i> Send Email
                        </a>
                        @if($employee)
                            <a href="" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-id-card me-2"></i> Employee Details
                            </a>
                        @endif
                        <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                            <i class="fas fa-key me-2"></i> Reset Password
                        </button>
                        @if($user->id !== auth()->id())
                            <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteUserModal">
                                <i class="fas fa-trash me-2"></i> Delete User
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Detailed Information -->
        <div class="col-xl-8 col-lg-7">
            <!-- Employee Information -->
            @if($employee)
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-briefcase me-2"></i> Employee Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Full Name</label>
                            <p class="mb-0 fw-medium">{{ $user->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Email Address</label>
                            <p class="mb-0">{{ $user->email }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Employee Code</label>
                            <p class="mb-0 fw-medium">{{ $employee->employee_code }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Position</label>
                            <p class="mb-0">{{ $employee->position ?? 'Not specified' }}</p>
                        </div>
                        @if($employee->team)
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Team</label>
                            <p class="mb-0">
                                <a href="" class="text-decoration-none">
                                    {{ $employee->team->name }}
                                </a>
                            </p>
                        </div>
                        @endif
                        @if($employee->nrc)
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">NRC Number</label>
                            <p class="mb-0">{{ $employee->nrc }}</p>
                        </div>
                        @endif
                        @if($employee->address)
                        <div class="col-12 mb-3">
                            <label class="form-label text-muted small">Address</label>
                            <p class="mb-0">{{ $employee->address }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="card-header bg-light">
                <h5 class="modal-title" id="resetPasswordModalLabel">Reset Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to reset the password for <strong>{{ $user->name }}</strong>?</p>
                <p class="text-muted small">A new temporary password will be generated and sent to their email address.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning">Reset Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete User Modal -->
@if($user->id !== auth()->id())
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">Delete User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone.
                </div>
                <p>Are you sure you want to delete <strong>{{ $user->name }}</strong>?</p>
                <p class="text-muted small">This will permanently remove the user and all associated data.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete User</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<style>
.avatar-xl {
    position: relative;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.btn {
    transition: all 0.3s ease;
}

.badge {
    font-size: 0.75rem;
}

@media (max-width: 768px) {
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
    
    .d-flex.gap-2 {
        justify-content: center;
    }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    });
});
</script>
@endpush
@endsection