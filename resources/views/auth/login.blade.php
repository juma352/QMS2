 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | QMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

    <!-- Styles -->
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #159ed5, #00A79D);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .login-container {
            background: #fff;
            border-radius: 12px;
            padding: 2.5rem;
            max-width: 420px;
            width: 100%;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h2 {
            margin: 0;
            color: #159ed5;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
        }

        .form-group input:focus {
            border-color: #159ed5;
            outline: none;
        }

        .form-actions {
            margin-top: 1rem;
        }

        .form-actions button {
            width: 100%;
            background: #159ed5;
            border: none;
            color: #fff;
            padding: 0.75rem;
            font-size: 1rem;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .form-actions button:hover {
            background: #007ca0;
        }

        .form-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.95rem;
        }

        .form-footer a {
            color: #159ed5;
            text-decoration: none;
        }

        .error-message {
            color: red;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            text-align: center;
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }

        .input-icon input {
            padding-left: 2.5rem;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-header">
            <h2>QMS Login</h2>
            <p>Please enter your credentials</p>
        </div>

        @if(session('error'))
            <div class="error-message">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="error-message">
                <ul style="padding-left: 1rem; list-style: disc; text-align: left;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group input-icon">
                <label for="email">Email Address</label>
                <i class="fas fa-envelope"></i>
                <input type="email" id="email" name="email" placeholder="you@example.com" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group input-icon">
                <label for="password">Password</label>
                <i class="fas fa-lock"></i>
                <input type="password" id="password" name="password" placeholder="Enter password" required>
            </div>

            <div class="form-actions">
                <button type="submit">Login</button>
            </div>
        </form>

        <div class="form-footer">
            <p><a href="#">Forgot password?</a></p>
            <p>Don't have an account? <a href="{{ route('register') }}">Register</a></p>
        </div>
    </div>

</body>
</html>
