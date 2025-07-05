@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">User Profile</h1>
            </div>

            <!-- User Details Card -->
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Profile Details</h5>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <h1>{{$user->employee['employee_code']}}</h1>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">Name</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $user->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">Email</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $user->email ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">Joined</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $user->created_at ? $user->created_at->format('d M Y') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    
                </div>
            </div>
        </main>
    </div>
</div>
@endsection