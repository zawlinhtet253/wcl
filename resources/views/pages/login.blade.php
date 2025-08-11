<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Win Consulting Ltd') }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        /* Custom responsive styles */
        .logo-responsive {
            width: 200px;
            max-width: 80%;
            height: auto;
        }
        
        .company-title {
            font-size: 2.5rem;
        }
        
        .form-container {
            width: 25%;
            min-width: 300px;
        }
        
        /* Mobile-first responsive design */
        @media (max-width: 768px) {
            .form-container {
                width: 90%;
                min-width: unset;
                padding: 0 15px;
            }
            
            .company-title {
                font-size: 1.8rem;
                margin-top: 1rem;
            }
            
            .logo-responsive {
                width: 150px;
            }
            
            .btn-responsive {
                width: 100% !important;
            }
            
            .container {
                padding: 0 10px;
            }
        }
        
        @media (max-width: 480px) {
            .company-title {
                font-size: 1.5rem;
                line-height: 1.3;
            }
            
            .logo-responsive {
                width: 120px;
            }
            
            .form-container {
                width: 95%;
            }
            
            .btn-responsive {
                padding: 12px 20px !important;
            }
            
            .mt-5 {
                margin-top: 2rem !important;
            }
        }
        
        /* Extra small devices */
        @media (max-width: 360px) {
            .company-title {
                font-size: 1.3rem;
            }
            
            .logo-responsive {
                width: 100px;
            }
        }
    </style>
</head>
<body class="dg-dark">
    <div class="container-fluid">
        <div class="text-center mt-5">
            <img src="{{asset('Logo/logo.png')}}" alt="Win Consulting Logo" class="mt-2 logo-responsive">
            <div class="company-title mt-3">Win Consulting Limited</div>
        </div>
        <div class="mt-4 mx-auto form-container">
            @if ($errors->any())
                <ul class="text-danger">
                    @foreach ($errors->all() as $error)
                        <p class="mb-2">{{ $error }}</p>
                    @endforeach
                </ul>
            @endif
            <form class="d-flex flex-column gap-3" action="{{ route('login') }}" method="POST">
                @csrf

                <div class="mb-2">
                    <label class="form-label" for="email">Email</label>
                    <input
                        id="email"
                        class="form-control form-control-lg"
                        type="email"
                        placeholder="Enter your Email"
                        name="email"
                        required
                    />
                </div>
                
                <div class="mb-3">
                    <label class="form-label" for="password">Password</label>
                    <input
                        id="password"
                        class="form-control form-control-lg"
                        type="password"
                        placeholder="Enter your password"
                        name="password"
                        required
                    />
                </div>
                
                <button
                    type="submit"
                    class="btn btn-primary py-3 rounded-pill btn-responsive mx-auto"
                    style="width: 50%;"
                >
                    Login
                </button>    
            </form>
        </div>
    </div>
</body>
</html>