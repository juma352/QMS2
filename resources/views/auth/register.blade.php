<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | QMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #159ed5, #00A79D);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .auth-box {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }
        h2 {
            text-align: center;
            color: #159ed5;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            font-weight: 600;
            display: block;
            margin-bottom: 0.5rem;
        }
        .form-group input {
            width: 100%;
            padding: 0.7rem;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .btn {
            width: 100%;
            padding: 0.75rem;
            background: #159ed5;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 1rem;
        }
        .btn:hover {
            background: #007ca0;
        }
        .error {
            color: red;
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }
        .switch-link {
            text-align: center;
            margin-top: 1rem;
        }
        .switch-link a {
            color: #159ed5;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="auth-box">
        <h2>Register for QMS</h2>
        @if($errors->any())
            <div class="error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" required>
            </div>
            <button class="btn" type="submit">Register</button>
        </form>
        <div class="switch-link">
            <p>Already have an account? <a href="{{ route('login') }}">Login</a></p>
        </div>
    </div>
</body>
</html>
