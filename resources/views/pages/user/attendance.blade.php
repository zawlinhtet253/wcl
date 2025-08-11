@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col bg-light transition-bg pt-20 pb-2" style="background: var(--bg, #f5f7fa);">
    <main class="flex-1 w-full max-w-3xl mx-auto p-3">
        <!-- Success/Error Messages -->
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0" style="background: var(--surface, #fff); color: var(--text, #1e293b);">
            <div class="card-body p-5">
                

                @if ($todayAttendance && $todayAttendance->check_out_time == null)
                    <!-- Display Check-in Data -->
                    <div class="card mb-4 border-0" style="background: var(--surface, #fff);">
                        <div class="card-body text-center">
                            <div class="mb-4">
                                <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/8f5f1d46-48be-4039-bb13-3eb35bdd5b7a.png" alt="Digital badge for today's attendance" class="rounded-circle mx-auto" style="width: 96px; height: 96px;" onerror="this.style.display='none';this.parentElement.innerText='Status';">
                            </div>
                            <div class="h5 font-weight-semibold mb-1 text-primary">Today's Status: <span class="font-weight-bold text-success">{{ $todayAttendance->name }}</span></div>
                            <div class="text-muted">Clocked In at <span class="font-weight-semibold">{{ \Carbon\Carbon::parse($todayAttendance->check_in_time)->toTimeString() }}</span></div>
                            @if ($todayAttendance->check_in_latitude && $todayAttendance->check_in_longitude)
                                <div class="text-muted mt-2">
                                    Check-in Location: 
                                    <a href="https://www.google.com/maps?q={{ $todayAttendance->check_in_latitude }},{{ $todayAttendance->check_in_longitude }}" target="_blank" class="text-accent underline hover:text-primary">View on Map</a>
                                </div>
                            @endif
                            <div class="text-muted mt-2">Status: <span class="font-weight-semibold">{{ $todayAttendance->status == 0 ? 'Incomplete' : 'Complete' }}</span></div>
                        </div>
                    </div>

                    <!-- Check-out Form -->
                    <form action="{{ route('user.attendance.checkout') }}" method="POST" class="needs-validation" novalidate id="checkout-form">
                        @csrf
                        <input type="hidden" name="attendance_id" value="{{ $todayAttendance->id }}">
                        <input type="hidden" name="check_out_latitude" id="check_out_latitude">
                        <input type="hidden" name="check_out_longitude" id="check_out_longitude">
                        <div class="text-center d-none mb-3" id="loading-indicator-checkout">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Fetching location...</span>
                            </div>
                            <p class="mt-2 text-muted">Fetching location...</p>
                        </div>
                        <div class="text-danger mb-3" id="location-error-checkout"></div>
                        <button type="submit" class="btn btn-danger w-100" id="checkout-btn" disabled>Check Out</button>
                    </form>
                @else
                    <!-- Check-in Form for Mobile (sm) -->
                    <div class="d-block d-md-none">
                        <form action="{{ route('attendance.store') }}" method="POST" class="needs-validation" novalidate id="checkin-form-mobile">
                            @csrf
                            <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                            <input type="hidden" name="check_in_latitude" id="check_in_latitude_mobile">
                            <input type="hidden" name="check_in_longitude" id="check_in_longitude_mobile">
                            <div class="mb-3">
                                <label for="name_mobile" class="form-label font-weight-medium">Attendance Type</label>
                                <select name="name" id="name_mobile" class="form-select" required onchange="this.form.submit()">
                                    <option value="">Select Attendance Type</option>
                                    <option value="Work From Home">Work From Home</option>
                                    <option value="In-Office">In-Office</option>
                                    <option value="Client Site">Client Site</option>
                                    <option value="Leave">Leave</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please select an attendance type.
                                </div>
                            </div>
                            <div class="text-center d-none mb-3" id="loading-indicator-checkin-mobile">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Fetching location...</span>
                                </div>
                                <p class="mt-2 text-muted">Fetching location...</p>
                            </div>
                            <div class="text-danger mb-3" id="location-error-checkin-mobile"></div>
                        </form>
                    </div>

                    <!-- Check-in Form for Desktop (md and above) -->
                    <div class="d-none d-md-block mx-auto w-50">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h1 class="h4 mb-0 text-primary font-weight-bold d-flex align-items-center gap-2">
                                <i class="fa fa-clock"></i> Attendance
                            </h1>
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-sm text-muted" id="geo-status">Not enabled</span>
                                <button aria-label="Geolocation verification" onclick="toggleGeo()" class="btn btn-sm btn-light border-0">
                                    <i id="geo-icon" class="fa-solid fa-location-crosshairs text-xl"></i>
                                </button>
                            </div>
                        </div>
                        <form action="{{ route('attendance.store') }}" method="POST" class="needs-validation" novalidate id="checkin-form-desktop">
                            @csrf
                            <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                            <input type="hidden" name="check_in_latitude" id="check_in_latitude_desktop">
                            <input type="hidden" name="check_in_longitude" id="check_in_longitude_desktop">
                            <div class="mb-3">
                                <label for="name_desktop" class="form-label font-weight-medium">Attendance Type</label>
                                <select name="name" id="name_desktop" class="form-select" required>
                                    <option value="">Select Attendance Type</option>
                                    <option value="Work From Home">Work From Home</option>
                                    <option value="In-Office">In-Office</option>
                                    <option value="Client Site">Client Site</option>
                                    <option value="Leave">Leave</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please select an attendance type.
                                </div>
                            </div>
                            <div class="text-center d-none mb-3" id="loading-indicator-checkin-desktop">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Fetching location...</span>
                                </div>
                                <p class="mt-2 text-muted">Fetching location...</p>
                            </div>
                            <div class="text-danger mb-3" id="location-error-checkin-desktop"></div>
                            <button type="submit" class="btn btn-primary w-100" id="checkin-btn-desktop" disabled>Submit Attendance</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <script>
        function getLocation(formId, latFieldId, lonFieldId, buttonId, errorId, loadingId) {
            const submitBtn = document.getElementById(buttonId);
            const errorDiv = document.getElementById(errorId);
            const loadingIndicator = document.getElementById(loadingId);

            // Show loading indicator
            loadingIndicator.classList.remove('d-none');

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        document.getElementById(latFieldId).value = position.coords.latitude;
                        document.getElementById(lonFieldId).value = position.coords.longitude;
                        if (submitBtn) {
                            submitBtn.disabled = false;
                        }
                        errorDiv.textContent = '';
                        loadingIndicator.classList.add('d-none');
                    },
                    (error) => {
                        let errorMessage = 'Unable to retrieve location. Please allow location access.';
                        if (error.code === error.PERMISSION_DENIED) {
                            errorMessage = 'Location access denied. Please enable location in your browser settings. <a href="https://support.google.com/chrome/answer/142065?hl=en" target="_blank">Learn how</a>';
                        }
                        errorDiv.innerHTML = errorMessage;
                        if (submitBtn) {
                            submitBtn.disabled = true;
                        }
                        loadingIndicator.classList.add('d-none');
                    }
                );
            } else {
                errorDiv.textContent = 'Geolocation is not supported by this browser.';
                if (submitBtn) {
                    submitBtn.disabled = true;
                }
                loadingIndicator.classList.add('d-none');
            }
        }

        // Run geolocation for check-in form (mobile)
        if (document.getElementById('checkin-form-mobile')) {
            getLocation('checkin-form-mobile', 'check_in_latitude_mobile', 'check_in_longitude_mobile', null, 'location-error-checkin-mobile', 'loading-indicator-checkin-mobile');
        }

        // Run geolocation for check-in form (desktop)
        if (document.getElementById('checkin-form-desktop')) {
            getLocation('checkin-form-desktop', 'check_in_latitude_desktop', 'check_in_longitude_desktop', 'checkin-btn-desktop', 'location-error-checkin-desktop', 'loading-indicator-checkin-desktop');
        }

        // Run geolocation for check-out form
        if (document.getElementById('checkout-form')) {
            getLocation('checkout-form', 'check_out_latitude', 'check_out_longitude', 'checkout-btn', 'location-error-checkout', 'loading-indicator-checkout');
        }

        // Bootstrap form validation with confirmation for checkout
        (function () {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    const latField = form.id.includes('checkin') ? (form.id === 'checkin-form-mobile' ? 'check_in_latitude_mobile' : 'check_in_latitude_desktop') : 'check_out_latitude';
                    if (!form.checkValidity() || !document.getElementById(latField).value) {
                        event.preventDefault();
                        event.stopPropagation();
                    } else if (form.id === 'checkout-form') {
                        event.preventDefault(); // Prevent submission until confirmed
                        if (confirm('Are you sure you want to check out?')) {
                            form.submit(); // Submit if confirmed
                        }
                        return; // Exit to avoid adding 'was-validated' prematurely
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();

        // Fake geolocation toggle for UI consistency
        let geoEnabled = false;
        function toggleGeo() {
            geoEnabled = !geoEnabled;
            document.getElementById('geo-icon').classList.toggle('text-primary', geoEnabled);
            document.getElementById('geo-status').textContent = geoEnabled ? 'Verified' : 'Not enabled';
        }
        </script>
</div>
@endsection