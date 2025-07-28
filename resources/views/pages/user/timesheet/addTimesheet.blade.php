@extends('layouts.app')

@section('content')
    <div class="bg-white p-4 rounded shadow-lg w-100 m-auto mt-5" style="max-width: none;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-center mb-0">Add New Timesheet</h2>
            <a href="{{ route('user.timesheet') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Timesheets
            </a>
        </div>

        <div class="card-body">
            @if (session('message'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <form id="timeSheetForm" novalidate method="POST" action="{{ route('timesheet.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label for="client_id" class="form-label required-field">Client <span class="text-danger">*</span></label>
                        <select name="client_id" id="client_id" class="form-select @error('client_id') is-invalid @enderror" required>
                            <option value="">Select Client</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="description" class="form-label required-field">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Describe what you worked on..." required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="fromDateTime" class="form-label required-field">From <span class="text-danger">*</span></label>
                        <input type="datetime-local" 
                               class="form-control @error('from') is-invalid @enderror" 
                               name="from" id="fromDateTime" 
                               value="{{ old('from') }}" required>
                        @error('from')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="toDateTime" class="form-label required-field">To <span class="text-danger">*</span></label>
                        <input type="datetime-local" 
                               class="form-control @error('to') is-invalid @enderror" 
                               name="to" id="toDateTime" 
                               value="{{ old('to') }}" required>
                        @error('to')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('user.timesheet') }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-clock me-2"></i>Submit Timesheet
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('fromDateTime').addEventListener('change', function () {
            const toField = document.getElementById('toDateTime');
            toField.setAttribute('min', this.value);
            
            // If 'to' time is before 'from' time, clear it
            if (toField.value && toField.value <= this.value) {
                toField.value = '';
            }
        });

        // Form validation
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
@endsection

@push('styles')
<style>
    .required-field {
        font-weight: 600;
    }
    .text-danger {
        color: #dc3545 !important;
    }
</style>
@endpush