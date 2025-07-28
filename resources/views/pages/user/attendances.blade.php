@extends('layouts.app')

@section('content')
    <div>
        <form action="{{ route('user.attendance') }}" method="GET">
            <input type="text"
                class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                name="date_range"
                id="date-range" 
                placeholder="Select Date Range"
                autocomplete="off">
        </form>
    </div>
    <div class="bg-white p-4 rounded shadow-lg w-100 m-auto mt-5" style="max-width: none;">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {{-- If user hasn't submitted attendance today, show the form --}}
        @if (!$todayAttendance && $user->level < 3)
            <h2 class="text-center mb-4">Submit Today's Attendance</h2>
            <form action="{{ route('attendance.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                <div class="mb-3">
                    <label for="name" class="form-label">Attendance Type</label>
                    <select name="name" id="name" class="form-select" required>
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

                <button type="submit" class="btn btn-primary w-100">Submit Attendance</button>
            </form>
        @endif

        {{-- Show user's own attendance if submitted --}}
        @if ($todayAttendance)
            {{-- Show user's own attendance --}}
            <h2 class="text-center mb-4">Your Attendance Today</h2>
            <div class="table-responsive mb-4">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Employee Code</th>
                            <th>Attendance Type</th>
                            <th>Date</th>
                            <th>Time</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $employee->employee_code }}</td>
                            <td>{{ $todayAttendance->name }}</td>
                            <td>{{ $todayAttendance->created_at->timezone('Asia/Yangon')->toDateString() }}</td>
                            <td>{{ $todayAttendance->created_at->timezone('Asia/Yangon')->format('H:i') }}</td>
                            
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif

        {{-- Show team/all attendance for level 2 and 3 users --}}
        @if (($user->level == 2 || $user->level == 3) && !$attendances->isEmpty())
            @if ($todayAttendance)
                <hr class="my-4">
            @endif
            <h3 class="text-center mb-4">
                {{ $user->level == 2 ? 'Team Attendance Today' : 'All Attendance Today' }}
            </h3>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Employee Code</th>
                            <th>Attendance Type</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Approved By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attendances as $attendance)
                            <tr class="{{ $attendance->employee_id == $employee->id ? 'table-primary' : '' }}">
                                <td>{{ $attendance->employee->user->name }}</td>
                                <td>{{ $attendance->employee->employee_code }}</td>
                                <td>{{ $attendance->name }}</td>
                                <td>{{ $attendance->created_at->timezone('Asia/Yangon')->toDateString() }}</td>
                                <td>{{ $attendance->created_at->timezone('Asia/Yangon')->format('H:i') }}</td>
                                <td>
                                    @if (!$attendance->status)
                                        <form action="{{ route('attendance.approve', ['attendance' => $attendance->id]) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-warning btn-sm">
                                                <i class="fa-solid fa-hourglass-half"></i>
                                            </button>
                                        </form>
                                    @else
                                        <p class="badge bg-success">
                                            <i class="fa-solid fa-check"></i>
                                        </p>
                                    @endif
                                </td>
                                <td>{{ $attendance->status ? $attendance->approveBy->user->name : '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @elseif (($user->level == 2 || $user->level == 3) && $attendances->isEmpty())
            @if ($todayAttendance)
                <hr class="my-4">
            @endif
            <p class="text-center text-muted">
                {{ $user->level == 2 ? 'No team attendance records found for today.' : 'No attendance records found for today.' }}
            </p>
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