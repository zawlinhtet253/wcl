@extends('layouts.app')

@section('content')
    <!-- <div>
        <form action=""  class="d-flex justify-content-between">
            <input type="text">                
            <select name="team" id="team" class="form-select w-25">
                <option value="">Select Team</option>
                @foreach ($clients as $client)
                    <option value="{{$client->team->name}}">{{$client->team->name}}</option>
                @endforeach
            </select>
            <select name="industry_type" id="industry_type" class="form-select w-25">
                <option value="">Select Clinet Type</option>
                @foreach ($clients as $client)
                    <option value="{{$client->industry_type}}">{{$client->industry_type}}</option>
                @endforeach
            </select>
            <button class="btn btn-primary" type="submit">Search</button>
        </form>
    </div> -->
    <div class="bg-white p-4 rounded shadow-lg w-100 m-auto mt-5" style="max-width: 1200px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-4">Clients</h2>
            <a href="{{ route('admin.client.create') }}" class="btn btn-primary mb-4">
                <i class="bi bi-plus"></i> Add Client
            </a>
        </div>
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Client Code</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Team Code</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clients as $client)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->industry_type }}</td>
                            <td>{{ $client->code }}</td>
                            <td>{{ $client->phone ?? 'အချက်အလက်မရှိ' }}</td>
                            <td>{{ $client->email ?? 'အချက်အလက်မရှိ' }}</td>
                            <td>{{ $client->address ?? 'အချက်အလက်မရှိ' }}</td>
                            <td>{{ $client->team->code ?? 'အဖွဲ့မရှိ' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Client မရှိပါ။</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection