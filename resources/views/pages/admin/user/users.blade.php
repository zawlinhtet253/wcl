@extends('layouts.app')
@section('content')
    <form action="{{ route('admin.users') }}" class="d-flex justify-content-evenly">
        <input 
            type="text" 
            class="form-control w-25" 
            name="search" 
            id="search-form" 
            placeholder="Username or Email"
        >
        <button type="submit" class="btn btn-primary">Serach</button>
    </form>
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="bg-white p-4 rounded shadow-lg w-100 m-auto mt-5" style="max-width: 1200px;">
        <div class="d-flex justify-content-between mb-4">
            <div class="d-flex align-items-center mb-3">
                <h2 class="mb-0 me-3">Users List</h2>
                <span class="badge bg-success rounded-pill">{{ $users->count() }}</span>
            </div>
        <a href="{{ route('admin.user.create') }}" class="btn">
            <i class="fas fa-plus"></i> Add user
        </a>
        </div>
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Team</th>
                    <th>Code</th>
                    <th>Position</th>
                    <th>Address</th>
                    <th>NRC</th>
                    <th>Level</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>                        
                        <td>{{ $user->employee->team->code }}</td>
                        <td>{{ $user->employee->employee_code ?? '-' }}</td>    
                        <td>{{ $user->employee->position ?? '-' }}</td>
                        <td>{{ $user->employee->address ?? '-' }}</td>
                        <td>{{ $user->employee->nrc ?? '-' }}</td>
                        <td>
                            @if ($user->level == 1)
                                User
                            @elseif ($user->level == 2)
                                Team Leader
                            @else
                                Admin
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.user.show', $user->id) }}" 
                                    class="btn btn-sm btn-outline-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.user.edit', $user->id) }}" 
                                    class="btn btn-sm btn-outline-warning mx-3" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.user.delete', $user->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">ဒေတာမရှိပါ။</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection