<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Authentication</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="d-flex justify-content-center align-items-center min-vh-100 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-header text-center">
                        <img src="{{ asset('Logo/logo.png') }}" alt="WCL Logo" class="mt-2" style="width: 100px;">
                        <h4 class="mt-2">Win Consulting Limited</h4>
                        
                    </div>
                    <div class="card-body">
                        <h5 class="mt-1 text-center">Two-Factor Authentication</h5>
                        @if (isset($needs_setup) && $needs_setup)
                            <div class="text-center mb-4">
                                <p class="text-muted">Scan the QR code below with your Google Authenticator app to set up two-factor authentication.</p>
                                {!! $QR_Image !!}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('2fa.verify.post') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="code" class="form-label">Enter the code from Google Authenticator:</label>
                                <input type="text" name="code" id="code" class="form-control" required>
                                @error('code')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-block">Verify</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>