@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="card shadow mx-auto w-50" style="max-width: 1200px;">
            <div class="card-body p-5">
                <h2 class="card-title text-center mb-4">Create User</h2>
                <form action="{{ route('admin.user.store') }}" method="POST">
                    @csrf
                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Password Confirmation -->
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                        @error('password_confirmation')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Employee Code -->
                    <div class="mb-3">
                        <label for="employee_code" class="form-label">Employee Code</label>
                        <input type="text" name="employee_code" id="employee_code" class="form-control" placeholder="e.g., WCL-001" required>
                        @error('employee_code')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Team -->
                    <div class="mb-3">
                        <label for="team_id" class="form-label">Team</label>
                        <select name="team_id" id="team_id" class="form-select" required>
                            <option value="" disabled selected>Select a team</option>
                            @foreach($teams as $team)
                                <option value="{{ $team->id }}">{{ $team->name }}</option>
                            @endforeach
                        </select>
                        @error('team_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Position -->
                    <div class="mb-3">
                        <label for="position" class="form-label">Position</label>
                        <input type="text" name="position" id="position" class="form-control" placeholder="e.g., Manager, Developer">
                        @error('position')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Submit Button -->
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-100">Create User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection