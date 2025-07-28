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
        <h3>Clients</h3>
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
                        <th>Code</th>
                        <th>Team Code</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clients as $client)
                        <tr>
                            <td>{{ $client->id }}</td>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->industry_type }}</td>
                            <td>{{ $client->code }}</td>
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