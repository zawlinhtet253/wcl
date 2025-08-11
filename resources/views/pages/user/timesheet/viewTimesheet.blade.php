<!-- resources/views/pages/user/attendance.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="bg-white p-4 rounded shadow-lg w-100 m-auto mt-5" style="max-width: none;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-center mb-0">Timesheet Details</h2>
            <div>
                <a href="{{ route('user.timesheet') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Timesheets
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-clock me-2"></i>Timesheet Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Client:</label>
                                <div class="border rounded p-2 bg-light">
                                    <span class="badge bg-info fs-6">{{ $timesheet->client->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Date:</label>
                                <div class="border rounded p-2 bg-light">
                                    {{ \Carbon\Carbon::parse($timesheet->from)->format('F d, Y') }}
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Start Time:</label>
                                <div class="border rounded p-2 bg-light">
                                    {{ \Carbon\Carbon::parse($timesheet->from)->format('h:i A') }}
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">End Time:</label>
                                <div class="border rounded p-2 bg-light">
                                    {{ \Carbon\Carbon::parse($timesheet->to)->format('h:i A') }}
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold text-muted">Description:</label>
                                <div class="border rounded p-3 bg-light">
                                    {{ $timesheet->description }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-calculator me-2"></i>Summary
                        </h5>
                    </div>
                    <div class="card-body">
                        @php
                            $from = \Carbon\Carbon::parse($timesheet->from);
                            $to = \Carbon\Carbon::parse($timesheet->to);
                            $totalMinutes = abs($to->diffInMinutes($from)); // Use abs() for positive duration
                            $hours = floor($totalMinutes / 60);
                            $minutes = $totalMinutes % 60;
                        @endphp
                        
                        <div class="text-center">
                            <div class="mb-3">
                                <h3 class="text-success mb-1">{{ sprintf('%02d:%02d', $hours, $minutes) }}</h3>
                                <small class="text-muted">Total Duration</small>
                            </div>
                            
                            <hr>
                            
                            <div class="row text-center">
                                <div class="col-6">
                                    <h4 class="text-primary mb-1">{{ $hours }}</h4>
                                    <small class="text-muted">Hours</small>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-info mb-1">{{ $minutes }}</h4>
                                    <small class="text-muted">Minutes</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
    .bg-light {
        background-color: #f8f9fa!important;
    }
</style>
@endpush