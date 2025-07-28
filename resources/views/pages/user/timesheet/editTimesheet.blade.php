@extends('layouts.app')

@section('content')
    <div class="bg-white p-4 rounded shadow-lg w-100 m-auto mt-5" style="max-width: none;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-center mb-0">Edit Timesheet</h2>
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

            <form id="timeSheetForm" novalidate method="POST" action="{{ route('timesheet.update', $timesheet->id) }}">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-12">
                        <label for="client_id" class="form-label required-field">Client</label>
                        <select name="client_id" id="client_id" class="form-select @error('client_id') is-invalid @enderror" required>
                            <option value="">Select Client</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id', $timesheet->client_id) == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="description" class="form-label required-field">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" required>{{ old('description', $timesheet->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="fromDateTime" class="form-label required-field">From</label>
                        <input type="datetime-local" 
                               class="form-control @error('from') is-invalid @enderror" 
                               name="from" id="fromDateTime" 
                               value="{{ old('from', \Carbon\Carbon::parse($timesheet->from)->format('Y-m-d\TH:i')) }}" 
                               required>
                        @error('from')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="toDateTime" class="form-label required-field">To</label>
                        <input type="datetime-local" 
                               class="form-control @error('to') is-invalid @enderror" 
                               name="to" id="toDateTime" 
                               value="{{ old('to', \Carbon\Carbon::parse($timesheet->to)->format('Y-m-d\TH:i')) }}" 
                               required>
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
                                <i class="fas fa-save me-2"></i>Update Timesheet
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
            document.getElementById('toDateTime').setAttribute('min', this.value);
        });

        // Set initial min value for 'to' field
        document.addEventListener('DOMContentLoaded', function() {
            const fromValue = document.getElementById('fromDateTime').value;
            if (fromValue) {
                document.getElementById('toDateTime').setAttribute('min', fromValue);
            }
        });
    </script>
@endsection