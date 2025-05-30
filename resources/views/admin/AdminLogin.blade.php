<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS (Optional, for icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #007CE6, #1AA0E8);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 1rem;
            /* Add padding for smaller screens */
        }

        .login-form-container {
            background-color: #ffffff;
            padding: 3rem 2rem; /* Increased top/bottom padding */
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 550px; /* Increased max-width */
        }

        .login-form-container h2 {
            color: #333;
            margin-bottom: 1.5rem;
            font-weight: 600;
            text-align: center;
        }

        .form-control:focus {
            border-color: #007CE6;
            box-shadow: 0 0 0 0.25rem rgba(0, 124, 230, 0.27);
        }

        .btn-login {
            background-color: #007CE6;
            border-color: #007CE6;
            color: white;
            font-weight: 500;
            padding: 0.75rem;
            /* Slightly larger button */
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .btn-login:hover {
            background-color: #006AD5;
            border-color: #006AD5;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-right: 0;
            /* Remove border between icon and input */
        }

        .form-control {
            border-left: 0;
            /* Remove border between icon and input if icon is on left */
        }

        /* Adjust if icons are on the right */
        .input-group .form-control {
            border-right: 1px solid #ced4da;
            /* Default border */
            border-left: 0;
        }

        .input-group .input-group-text+.form-control {
            /* If icon is on the left */
            border-left: 0;
        }

        .input-group .form-control+.input-group-text {
            /* If icon is on the right */
            border-left: 0;
            border-right: 1px solid #ced4da;
        }


        .form-floating>.form-control:focus~label,
        .form-floating>.form-control:not(:placeholder-shown)~label {
            color: #007CE6;
            /* Change label color on focus or when filled */
        }

        .extra-links {
            text-align: center;
            margin-top: 1rem;
        }

        .extra-links a {
            color: #007CE6;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .extra-links a:hover {
            text-decoration: underline;
        }

        .alert-danger ul {
            margin-bottom: 0;
        }

        .invalid-feedback {
            display: block;
            /* Ensure error messages are shown */
        }
    </style>
</head>

<body>
    <div class="login-form-container">
        <h2>Admin Login</h2>

        @if (session('status'))
            <div class="alert alert-success mb-3">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="adminLoginForm" method="POST" action="{{ route('AdminLogin') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                        name="email" value="{{ old('email') }}" placeholder="Enter your email" required autofocus>
                </div>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3"> {{-- Changed mb-4 to mb-3 for consistency --}}
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        name="password" placeholder="Enter your password" required>
                </div>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-login mt-2">Login</button>
            </div>

            <div class="extra-links mt-3">
                {{-- <a href="#">Forgot password?</a> --}}
                {{-- <a href="{{ route('AdminRegister') }}">Create an account</a> --}}
            </div>
        </form>
    </div>

    <!-- Bootstrap JS (Optional, for certain components like dropdowns, modals, etc. Not strictly needed for this form) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    {{--
    <script>
        // Optional: Add JavaScript for form validation or submission handling here
        // document.getElementById('adminLoginForm').addEventListener('submit', function(event) {
        //     event.preventDefault(); // Prevent actual submission for this example
        //     // Add your login logic here
        //     const email = document.getElementById('email').value;
        //     const password = document.getElementById('password').value;
        //     console.log('Attempting login with:', { email, password });
        //     alert('Login form submitted (check console for details)! Implement your actual login logic here.');
        // });
    </script>
    --}}
</body>

</html>
