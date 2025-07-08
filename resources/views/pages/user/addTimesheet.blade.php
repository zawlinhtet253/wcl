@extends('layouts.app')

@section('content')
    <div class="bg-white p-4 rounded shadow-lg w-100 m-auto mt-5" style="max-width: none;">
        <h2 class="text-center">Timesheet</h2>
        <div class="card-body">
            @if (session('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <form id="timeSheetForm" novalidate method="POST" action="{{ route('timesheet.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label for="client_id" class="form-label required-field">Client</label>
                        <select name="client_id" id="client_id" class="form-select @error('client_id') is-invalid @enderror" required>
                            <option value="">Select Client</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client['id'] }}">{{ $client['name'] }}</option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="description" class="form-label required-field">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required></textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="fromDateTime" class="form-label required-field">From</label>
                        <input type="datetime-local" class="form-control @error('from') is-invalid @enderror" name="from" id="fromDateTime" required>
                        @error('from')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="toDateTime" class="form-label required-field">To</label>
                        <input type="datetime-local" class="form-control @error('to') is-invalid @enderror" name="to" id="toDateTime" required>
                        @error('to')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-clock me-2"></i>Submit Time Sheet
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @section('scripts')
        <script>
            document.getElementById('fromDateTime').addEventListener('change', function () {
                document.getElementById('toDateTime').setAttribute('min', this.value);
            });
        </script>
    @endsection
@endsection