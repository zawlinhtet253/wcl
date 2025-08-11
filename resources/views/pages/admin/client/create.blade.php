@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="card shadow mx-auto w-50" style="max-width: 1200px;">
            <div class="card-body p-5">
                <h2 class="card-title text-center mb-4">Create Client</h2>
                <form action="{{ route('admin.client.store') }}" method="POST">
                    @csrf
                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Client Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Industry Type -->
                    <div class="mb-3">
                        <label for="industry_type" class="form-label">Industry Type</label>
                        <input type="text" name="industry_type" id="industry_type" class="form-control" required>
                        @error('industry_type')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Client Code -->    
                    <div class="mb-3">
                        <label for="code" class="form-label">Client Code</label>
                        <input type="text" name="code" id="code" class="form-control" placeholder="e.g., CLT-001" required>
                        @error('code')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Phone -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control" placeholder="e.g., 0912345678">
                        @error('phone')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="e.g.,client@gmail.com:">
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Address -->
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" name="address" id="address" class="form-control" placeholder="e.g., 123 Main St, City, Country">
                        @error('address')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Team -->
                    <div class="mb-3">
                        @if (auth()->user()->level == 3)
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
                        @else
                            <label for="team_id" class="form-label">Team</label>
                            <select name="team_id" id="team_id" class="form-select" required>
                                <option value="{{ $employeeTeamId }}" selected>{{ $employeeTeamName ?? 'Your Team' }}</option>
                            </select>
                            @error('team_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Create Client</button>
                </form>
            </div>
        </div>
    </div>         
    
@endsection