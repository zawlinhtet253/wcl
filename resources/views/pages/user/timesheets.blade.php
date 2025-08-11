<!-- resources/views/pages/user/attendance.blade.php -->

@extends('layouts.app')

@section('content')
<div class="bg-white p-4 rounded shadow-lg w-100 m-auto mt-5" style="max-width: none;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-center mb-0">Timesheets</h2>
        <form action="{{ route('user.timesheet') }}" method="GET">
            <input 
                type="text" 
                id="date-range" 
                name="date_range"
                class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                autocomplete="off"
                placeholder="Select Date Range"
            />
        </form>
        <a href="{{ route('user.timesheet.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Timesheet
        </a>
    </div>


    @if(session('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($timesheets->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Client</th>
                        <th scope="col">Description</th>
                        <th scope="col">Start Time</th>
                        <th scope="col">End Time</th>
                        <th scope="col">Duration</th>
                        <th scope="col">Date</th>
                        <th scope="col">Team</th>
                        <th scope="col">Status</th>
                        <th scope="col">Approved_by</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($timesheets as $timesheet)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $timesheet->employee->user->name }} </td>

                            <td>
                                <span class="badge bg-info">
                                    {{ $timesheet->client->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 200px;" title="{{ $timesheet->description }}">
                                    {{ $timesheet->description }}
                                </div>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($timesheet->from)->format('H:i') }}
                                </small>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($timesheet->to)->format('H:i') }}
                                </small>
                            </td>
                            <td>
                                @php
                                    $from = \Carbon\Carbon::parse($timesheet->from);
                                    $to = \Carbon\Carbon::parse($timesheet->to);
                                    $minutes = abs($to->diffInMinutes($from)); // Use abs() for positive duration
                                    $hours = floor($minutes / 60);
                                    $remainingMinutes = $minutes % 60;
                                @endphp
                                <span class="badge bg-success">
                                    {{ sprintf('%02d:%02d', $hours, $remainingMinutes) }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($timesheet->from)->format('M d, Y') }}
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-warning">
                                    {{ $timesheet->employee->team->name }}
                                </span>
                            </td>
                            <td>
                                
                                @if (!$timesheet->status)
                                    <form action="{{ route('timesheet.approve' , ['timesheet' => $timesheet->id])}}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm">
                                            <i class="fa-solid fa-hourglass-half"></i>
                                        </button>
                                    </form>
                                @else
                                    <p class="badge bg-success">
                                        <i class="fa-solid fa-check"></i>
                                    </p>
                                @endif
                            </td>
                            <td>
                                {{ $timesheet->approveBy->user->name ?? 'N/A' }}
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('user.timesheet.show', $timesheet->id) }}" 
                                       class="btn btn-sm btn-outline-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if (auth()->user()->level === 1)
                                        @if (!$timesheet->status)
                                            <a href="{{ route('user.timesheet.edit', $timesheet->id) }}" 
                                                class="btn btn-sm btn-outline-warning mx-3" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('timesheet.destroy', $timesheet->id) }}" 
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this timesheet?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"> 
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        @if ($timesheet->status)
                                            <a href="{{ route('user.timesheet.edit', $timesheet->id) }}" 
                                                class="btn btn-sm btn-outline-warning mx-3" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('timesheet.destroy', $timesheet->id) }}" 
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this timesheet?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                    @endif
                                    
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary Cards -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Entries</h5>
                        <h3 class="text-primary">{{ count($timesheets) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Hours</h5>
                        @php
                            $totalMinutes = 0;
                            foreach($timesheets as $timesheet) {
                                $from = \Carbon\Carbon::parse($timesheet->from);
                                $to = \Carbon\Carbon::parse($timesheet->to);
                                $totalMinutes += abs($to->diffInMinutes($from)); // Use abs() for positive duration
                            }
                            $hours = floor($totalMinutes / 60);
                            $minutes = $totalMinutes % 60;
                        @endphp
                        <h3 class="text-success">{{ $hours }}h {{ $minutes }}m</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h5 class="card-title">This Month</h5>
                        @php
                            $thisMonthCount = collect($timesheets)->filter(function($timesheet) {
                                return \Carbon\Carbon::parse($timesheet->from)->isCurrentMonth();
                            })->count();
                        @endphp
                        <h3 class="text-info">{{ $thisMonthCount }}</h3>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <div class="mb-3">
                <i class="fas fa-clock fa-3x text-muted"></i>
            </div>
            <h4 class="text-muted">No Timesheets Found</h4>
            <p class="text-muted">You haven't created any timesheets yet.</p>
            <a href="{{ route('user.timesheet.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Your First Timesheet
            </a>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .table-responsive {
        border-radius: 0.375rem;
        overflow: hidden;
    }
    
    .btn-group .btn {
        border-radius: 0.25rem !important;
        margin-right: 2px;
    }
    
    .text-truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endpush