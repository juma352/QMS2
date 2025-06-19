<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | QMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

    <style>
        :root {
            --primary-color: #3b82f6;
            --primary-hover: #2563eb;
            --primary-gradient: linear-gradient(45deg, #3b82f6, #818cf8);
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --text-on-dark-bg: #e5e7eb;
            --text-muted-on-dark-bg: #9ca3af;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        body {
            font-family: 'Inter', sans-serif;
            background-image: linear-gradient(rgba(17, 24, 39, 0.7), rgba(17, 24, 39, 0.7)), url('{{ asset('images/bg_1.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .page-wrapper {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-grow: 1;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(25px) saturate(180%);
            -webkit-backdrop-filter: blur(25px) saturate(180%);
            border-radius: 20px;
            padding: 2.25rem; /* Reduced padding */
            max-width: 380px; /* More compact width */
            width: 100%;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.3);
            text-align: center;
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .login-header {
            margin-bottom: 1.75rem; /* Tightened margin */
        }

        .logo-icon {
            display: inline-block;
            font-size: 2rem;
            margin-bottom: 1rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
        }
        
        .login-header h2 {
            font-weight: 700;
            font-size: 1.75rem;
            color: var(--text-dark);
            margin-bottom: 0.25rem; /* Tightened margin */
        }

        .login-header p {
            color: var(--text-dark); /* Changed to black as requested */
            font-size: 0.95rem;
            font-weight: 500;
        }
        
        .form-group {
            margin-bottom: 1.1rem; /* Tightened margin */
            text-align: left;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.75rem; /* Reduced padding */
            border: 1px solid rgba(209, 213, 219, 0.5);
            border-radius: 10px;
            font-size: 0.95rem;
            background: rgba(255, 255, 255, 0.5);
            color: var(--text-dark);
            transition: all 0.3s ease;
        }
        
        .form-group input::placeholder {
            color: var(--text-light);
        }

        .form-group input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.8);
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
        }
        
        .form-group input:focus ~ i {
            color: var(--primary-color);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem; /* Tightened margin */
            font-size: 0.9rem;
        }

        .form-options .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .form-options label,
        .form-options a {
            color: var(--text-dark);
            font-weight: 500;
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .form-options a:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }

        .form-actions button {
            width: 100%;
            background-image: var(--primary-gradient);
            border: none;
            color: #fff;
            padding: 0.85rem; /* Reduced padding */
            font-size: 1rem;
            font-weight: 600;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(59, 130, 246, 0.25);
        }

        .form-actions button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
        }

        .form-actions button:active {
            transform: translateY(0);
            box-shadow: 0 5px 20px rgba(59, 130, 246, 0.25);
        }

        .public-resources {
            margin-top: 2.5rem; /* Reduced margin */
            text-align: center;
            animation: fadeInUp 1s ease-out 0.2s forwards;
            opacity: 0;
        }
        .public-resources h3 {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-muted-on-dark-bg);
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        .public-resources nav {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem 2rem;
        }
        .public-resources a {
            color: var(--text-on-dark-bg);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.2s;
        }
        .public-resources a:hover {
            color: var(--primary-color);
        }
        .public-resources a i {
            margin-right: 0.5rem;
            color: var(--text-muted-on-dark-bg);
            transition: color 0.2s;
        }
        .public-resources a:hover i {
            color: var(--primary-color);
        }
        
        .page-footer {
            width: 100%;
            text-align: center;
            padding-top: 2.5rem; /* Reduced padding */
            padding-bottom: 1.5rem;
            opacity: 0;
            animation: fadeInUp 1.2s ease-out 0.4s forwards;
        }
        .page-footer nav {
            display: flex;
            justify-content: center;
            gap: 2rem;
        }
        .page-footer a {
            color: var(--text-muted-on-dark-bg);
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.2s;
        }
        .page-footer a:hover {
            color: #fff;
        }
        
        .error-message {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(220, 38, 38, 0.3);
            color: #991b1b;
            font-size: 0.9rem;
            margin-bottom: 1.25rem;
            text-align: left;
            padding: 0.75rem 1rem;
            border-radius: 8px;
        }
    </style>
</head>
<body>

    <div class="page-wrapper">
        <main class="login-container">
            <div class="login-header">
                <div class="logo-icon">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h2>QMS Portal</h2>
                <p>Sign in to access your dashboard</p>
            </div>

            @if(session('error'))
                <div class="error-message">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="error-message">
                    <ul style="padding-left: 1.25rem; margin: 0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="Email Address" value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Password" required>
                    </div>
                </div>

                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" name="remember" id="remember" style="accent-color: var(--primary-color);">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="#">Forgot password?</a>
                </div>

                <div class="form-actions">
                    <button type="submit">Sign In Securely</button>
                </div>
            </form>
        </main>

        <section class="public-resources">
             <h3>Public Resources</h3>
             <nav>
                <a href="#"><i class="fas fa-file-alt"></i>Public Reports</a>
                <a href="#"><i class="fas fa-book-open"></i>Knowledge Base</a>
                <a href="#"><i class="fas fa-satellite-dish"></i>System Status</a>
             </nav>
        </section>
    </div>

    <footer class="page-footer">
        <nav>
            <a href="#">Help Center</a>
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
        </nav>
    </footer>

</body>
</html>