<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title>Login</title>
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(120deg, #ffecd2 0%, #fcb69f 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .glass-card {
            background: rgba(255,255,255,0.85);
            box-shadow: 0 8px 32px 0 rgba(31,38,135,0.18);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-radius: 22px;
            border: 1.5px solid rgba(255,255,255,0.28);
            max-width: 400px;
            width: 100%;
            padding: 2.5rem 2rem 2rem 2rem;
            margin: 2rem 0;
        }
        .login-logo {
            width: 90px;
            margin-bottom: 1.2rem;
        }
        .form-label {
            font-weight: 600;
            color: #ff6b01;
        }
        .input-group-text {
            background: #fff7f0;
            border: none;
            color: #ff6b01;
            font-size: 1.1rem;
        }
        .form-control:focus {
            border-color: #ffb347;
            box-shadow: 0 0 0 0.2rem rgba(255,107,1,0.08);
        }
        .btn-primary {
            background: linear-gradient(90deg, #ff6b01 0%, #ffb347 100%);
            border: none;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(255,107,1,0.13);
            transition: background 0.2s, box-shadow 0.2s;
        }
        .btn-primary:hover {
            background: #e65b00;
            box-shadow: 0 4px 16px rgba(255,107,1,0.18);
        }
        .alert-danger, .alert-success {
            border-radius: 12px;
            font-size: 0.98rem;
            padding: 0.7rem 1rem;
        }
        .forgot-link {
            color: #ff6b01;
            font-size: 0.97rem;
        }
        .forgot-link:hover {
            color: #e65b00;
            text-decoration: underline;
        }
        @media (max-width: 575.98px) {
            .glass-card {
                padding: 1.2rem 0.5rem 1rem 0.5rem;
                border-radius: 12px;
            }
            .login-logo {
                width: 60px;
            }
        }
    </style>
</head>
<body>

<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="glass-card">
        <div class="text-center mb-4">
            <img class="login-logo" src="{{ asset('image/logo/logo.png') }}" alt="Logo">
        </div>

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Session Status -->
        @if (session('status'))
            <div class="alert alert-success mb-3">
                {{ session('status') }}
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                    <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus autocomplete="username">
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input id="password" type="password" name="password" class="form-control" required autocomplete="current-password">
                </div>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                <label class="form-check-label" for="remember_me">Remember me</label>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot your password?</a>
                @endif

                <button type="submit" class="btn btn-primary px-4">Log in</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@livewireScripts
</body>
</html>
