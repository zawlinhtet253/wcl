<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="dg-dark">
    <div class="container">
        <div class="text-center mt-5">
            <img src="https://winthinassociates.com/images/logo/WinThinLogo.png" alt="" class="mt-2">
            <span class="fs-1">Win Consulting Limited</span>
        </div>
        <div class="mt-3 m-auto w-25">
            @if ($errors->any())
                <ul class="text-danger">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </ul>
            @endif
            <form class="d-flex flex-column gap-3" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-2">
                    <label class="form-label">Email</label>
                    <input
                    class="form-control"
                    type="email"
                    placeholder="Enter your Email"
                    name="email"
                    />
                </div>
                
                <div class="mb-2">
                    <label class="form-label">Password</label>
                    <input
                    class="form-control"
                    type="password"
                    placeholder="Enter your password"
                    name="password"
                    />
                </div>
                <button
                    type="submit"
                    class="btn btn-primary py-3 rounded-pill w-50 m-auto"
                >
                    Login
                </button>    
            </form>
        </div>
    </div>
</body>
</html>