@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <form action="{{ route('user.attendances') }}" method="GET" class="max-w-md">
            <div class="relative">
                <input type="text"
                    class="form-control w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                    name="date_range"
                    id="date-range"
                    placeholder="Select Date Range (e.g., 2025-07-01 - 2025-07-31)"
                    autocomplete="off"
                    value="{{ request()->input('date_range') }}">
            </div>
        </form>
    </div>
    <div class="bg-white p-4 rounded shadow-lg w-100 m-auto mt-5" style="max-width: none;">
       @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

               
                <!-- Attendance History -->
                <hr class="my-4">
                <h2 class="h4 text-center mb-4 text-primary font-weight-bold d-flex align-items-center justify-content-center gap-2">
                    <i class="fa fa-history"></i> Attendance History
                </h2>
                @if ($attendances->isEmpty())
                    <p class="text-center text-muted">
                        {{ $user->level == 1 ? 'No attendance records found.' : ($user->level == 2 ? 'No team attendance records found for today.' : 'No attendance records found for today.') }}
                    </p>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Employee Code</th>
                                    <th>Attendance Type</th>
                                    <th>Date</th>
                                    <th>Check-In Time</th>
                                    <th>Check-Out Time</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    @if ($user->level == 2 || $user->level == 3)
                                        <th>Action</th>
                                        <th>Approved By</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendances as $attendance)
                                    <tr class="{{ $attendance->employee_id == $employee->id ? 'table-primary' : '' }}">
                                        <td>{{ $attendance->employee->user->name }}</td>
                                        <td>{{ $attendance->employee->employee_code }}</td>
                                        <td>{{ $attendance->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($attendance->created_at)->toDateString() }}</td>
                                        <td>{{ \Carbon\Carbon::parse($attendance->check_in_time)->toTimeString() }}</td>
                                        <td>{{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->timezone('Asia/Singapore')->toTimeString() : 'N/A' }}</td>
                                        <td>
                                            @if ($attendance->check_in_latitude && $attendance->check_in_longitude)
                                                <a href="https://www.google.com/maps?q={{ $attendance->check_in_latitude }},{{ $attendance->check_in_longitude }}" target="_blank" class="text-accent underline hover:text-primary">Check-In</a>
                                            @endif
                                            @if ($attendance->check_out_latitude && $attendance->check_out_longitude)
                                                | <a href="https://www.google.com/maps?q={{ $attendance->check_out_latitude }},{{ $attendance->check_out_longitude }}" target="_blank" class="text-accent underline hover:text-primary">Check-Out</a>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($attendance->status == 0)
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @else
                                                <span class="badge bg-success"><i class="fa fa-check"></i> Approved</span>
                                            @endif
                                        </td>
                                        @if ($user->level == 2 || $user->level == 3)
                                            <td>
                                                @if ($attendance->status == 0)
                                                    <form action="{{ route('attendance.approve', ['attendance' => $attendance->id]) }}" method="POST">
                                                        @csrf
                                                        <button class="btn btn-warning btn-sm" title="Approve">
                                                            <i class="fa-solid fa-hourglass-half"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-muted">Approved</span>
                                                @endif
                                            </td>
                                            <td>{{ $attendance->approved_by ? $attendance->approvedBy->user->name : 'N/A' }}</td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
    </div>
@endsection

@section('styles')
<style>
    .table-primary {
        background-color: rgba(13, 110, 253, 0.1);
    }
</style>
@endsection

@section('scripts')
@endsection