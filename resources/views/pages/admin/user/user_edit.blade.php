```blade
@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Edit Profile</h1>
            <p class="text-muted mb-0">Update your personal information</p>
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
                        <div class="avatar-xl mx-auto mb-3">
                            @if($user->avatar ?? false)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" 
                                     class="rounded-circle img-fluid" style="width: 120px; height: 120px; object-fit: cover;">
                            @else
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white" 
                                     style="width: 120px; height: 120px; font-size: 48px; font-weight: 600;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Basic Info -->
                    <h4 class="mb-1">{{ $user->name }}</h4>
                    @if($user->employee)
                        <p class="text-muted mb-2">{{ $user->employee->position ?? 'Employee' }}</p>
                        <span class="badge bg-primary mb-3">{{ $user->employee->employee_code }}</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Edit Form -->
        <div class="col-xl-8 col-lg-7">
            <!-- Edit Information -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-briefcase me-2"></i> Update Information
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.user.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="row">
                            @if(auth()->user()->level >= 2)
                                <!-- Full Name -->
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label text-muted small">Full Name</label>
                                    <input type="text" name="name" id="name" 
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $user->name) }}"
                                           placeholder="Enter full name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Email Address -->
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label text-muted small">Email Address</label>
                                    <input type="email" name="email" id="email" 
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', $user->email) }}"
                                           placeholder="Enter email address"
                                           >
                                           
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Employee Code -->
                                <div class="col-md-6 mb-3">
                                    <label for="employee_code" class="form-label text-muted small">Employee Code</label>
                                    <input type="text" name="employee_code" id="employee_code" 
                                           class="form-control @error('employee_code') is-invalid @enderror"
                                           value="{{ old('employee_code', $user->employee->employee_code ?? '') }}"
                                           placeholder="Enter employee code"
                                           >
                                    @error('employee_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Position -->
                                <div class="col-md-6 mb-3">
                                    <label for="position" class="form-label text-muted small">Position</label>
                                    <input type="text" name="position" id="position" 
                                           class="form-control @error('position') is-invalid @enderror"
                                           value="{{ old('position', $user->employee->position ?? '') }}"
                                           placeholder="Enter position"
                                           >
                                    @error('position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Team -->
                                @if ($teams)
                                    <div class="col-md-6 mb-3">
                                        <label for="team_id" class="form-label text-muted small">Team</label>
                                        <select name="team_id" id="team_id" 
                                                class="form-control @error('team_id') is-invalid @enderror">
                                            <option value="">Select a team</option>
                                            @foreach($teams as $team)
                                                <option value="{{ $team->id }}" {{ old('team_id', $user->employee->team_id ?? '') == $team->id ? 'selected' : '' }}>
                                                    {{ $team->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('team_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif
                            @endif
                            <!-- NRC Number -->
                            <div class="col-md-6 mb-3">
                                <label for="nrc" class="form-label text-muted small">NRC Number</label>
                                <input type="text" name="nrc" id="nrc" 
                                       class="form-control @error('nrc') is-invalid @enderror"
                                       value="{{ old('nrc', $user->employee->nrc ?? '') }}"
                                       placeholder="Enter NRC number">
                                @error('nrc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Address -->
                            <div class="col-12 mb-3">
                                <label for="address" class="form-label text-muted small">Address</label>
                                <textarea name="address" id="address" 
                                          class="form-control @error('address') is-invalid @enderror"
                                          rows="4" placeholder="Enter address">{{ old('address', $user->employee->address ?? '') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Save Changes
                            </button>
                            <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

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
```