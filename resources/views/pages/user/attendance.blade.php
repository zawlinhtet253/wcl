@extends('layouts.app')

@section('content')
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

        @if ($todayAttendance)
            <h2 class="text-center mb-4">Attendance Records</h2>
            <div class="table-responsive mb-4">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>ID</th>
                            <th>Attendance Type</th>
                            <th>Date</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->employee->employee_code }}</td>
                            <td>{{ $todayAttendance->name }}</td>
                            <td>{{ $todayAttendance->created_at->timezone('Asia/Yangon')->toDateString() }}</td>
                            <td>{{ $todayAttendance->created_at->timezone('Asia/Yangon')->format('H:i') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <h2 class="text-center mb-4">Attendance</h2>
            <form action="{{ route('attendance.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <input type="hidden" name="employee_id" value="{{ auth()->user()->id }}">

                <div class="mb-3">
                    <label for="name" class="form-label">Attendance Type</label>
                    <select name="name" id="name" class="form-select" required>
                        <option value="">Select Type</option>
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
    </div>
@endsection

@section('styles')
@endsection

@section('scripts')
@endsection