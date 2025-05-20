<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS (Optional, for icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 2rem 1rem;
            /* Add padding for smaller screens and taller form */
        }

        .auth-form-container {
            /* Renamed from login-form-container for generality */
            background-color: #ffffff;
            padding: 2.5rem 2rem;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            /* Slightly wider to accommodate more fields if needed */
        }

        .auth-form-container h2 {
            color: #333;
            margin-bottom: 1.5rem;
            font-weight: 600;
            text-align: center;
        }

        .form-control:focus {
            border-color: #764ba2;
            box-shadow: 0 0 0 0.25rem rgba(118, 75, 162, 0.25);
        }

        .btn-submit-custom {
            /* Renamed from btn-login */
            background-color: #764ba2;
            border-color: #764ba2;
            color: white;
            font-weight: 500;
            padding: 0.75rem;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .btn-submit-custom:hover {
            background-color: #663f8c;
            border-color: #663f8c;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-right: 0;
        }

        .input-group .form-control {
            border-left: 0;
        }

        /* Ensure borders are correct when icon is on the left */
        .input-group .input-group-text+.form-control {
            border-left: 0;
        }


        .form-label {
            font-weight: 500;
            /* Slightly bolder labels */
        }

        .extra-links {
            text-align: center;
            margin-top: 1rem;
        }

        .extra-links a {
            color: #764ba2;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .extra-links a:hover {
            text-decoration: underline;
        }

        /* Style for file input */
        .form-control[type="file"] {
            /* Bootstrap handles most styling, but you can add specifics */
        }

        .form-control[type="file"]:not(:disabled):not([readonly]) {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="auth-form-container">
        <h2>Create Admin Account</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="registrationForm" method="POST" action="{{ route('AdminRegister') }}"
            enctype="multipart/form-data">
            {{-- <form id="registrationForm" method="POST" action="#" enctype="multipart/form-data"> --}}
            @csrf
            <div class="mb-3">
                <label for="fullName" class="form-label">Full Name</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                    <input type="text" class="form-control" id="fullName" name="full_name"
                        placeholder="Enter your full name" required>
                </div>
                @error('full_name')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="userName" class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control" id="userName" name="user_name"
                        placeholder="Choose a username" required>
                </div>
                @error('user_name')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="phoneNumber" class="form-label">Phone Number</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber"
                        placeholder="e.g., +1234567890">
                </div>
                @error('phone_number')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" class="form-control" id="email" name="email"
                        placeholder="Enter your email" required>
                </div>
                @error('email')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="Create a strong password" required>
                </div>
                @error('password')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    {{-- Corrected input tag below --}}
                    <input type="password" class="form-control" id="confirmPassword" name="password_confirmation"
                        placeholder="Confirm your password" required>
                </div>
                @error('password_confirmation')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>


            <div class="mb-4">
                <label for="picture" class="form-label">Profile Picture (Optional)</label>
                <input type="file" class="form-control" id="picture" name="picture" accept="image/*">
                @error('picture')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-submit-custom">Register</button>
            </div>

            <div class="extra-links mt-3">
                {{-- <a href="{{ route('AdminLogin.form') }}">Already have an account? Login</a> --}}
                <a href="{{ route('AdminLogin.form')}}">Already have an account? Login</a>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    {{-- <script>
        document.getElementById('registrationForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent actual submission for this example

            // Basic client-side validation (you'd want more robust validation)
            const password = document.getElementById('password').value;
            // const confirmPassword = document.getElementById('confirmPassword')?.value; // If using confirm password

            // if (password !== confirmPassword) { // If using confirm password
            //     alert("Passwords do not match!");
            //     return;
            // }

            // Gather form data
            const formData = new FormData();
            formData.append('full_name', document.getElementById('fullName').value);
            formData.append('user_name', document.getElementById('userName').value);
            formData.append('phoneNumber', document.getElementById('phoneNumber').value);
            formData.append('email', document.getElementById('email').value);
            formData.append('password', password);

            const pictureFile = document.getElementById('picture').files[0];
            if (pictureFile) {
                formData.append('picture', pictureFile);
            }

            console.log('Registration attempt with the following data:');
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }

            alert(
                'Registration form submitted (check console for details)! Implement your actual registration logic here.'
                );
            // Here you would typically send `formData` to your server using fetch() or XMLHttpRequest
        });
    </script> --}}
</body>

</html>
