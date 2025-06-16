<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'QMS Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Base Styling -->
    <style>
        :root {
            --primary: #007bff; /* Aligned with Bootstrap */
            --dark: #1f2937;
            --light: #f9fafb;
            --hover: #0056b3;
        }
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--light);
            color: #333;
        }
        .topbar {
            background: var(--primary);
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff;
        }
        .topbar .title {
            font-size: 1.25rem;
            font-weight: 600;
        }
        .sidebar {
            background: var(--dark);
            color: #fff;
            width: 240px;
            min-height: 100vh;
            padding-top: 1rem;
            transition: width 0.3s ease;
        }
        .sidebar.collapsed {
            width: 70px;
        }
        .sidebar a {
            display: block;
            color: #fff;
            padding: 0.75rem 1rem;
            text-decoration: none;
            transition: background 0.2s;
        }
        .sidebar a:hover {
            background: var(--primary);
        }
        .sidebar .icon {
            margin-right: 0.75rem;
        }
        .content {
            flex: 1;
            padding: 1rem; /* Reduced from 2rem */
            background: #f4f6f9;
            min-height: 100vh;
        }
        .layout {
            display: flex;
            flex-direction: row;
        }
        footer {
            text-align: center;
            padding: 0.75rem;
            border-top: 1px solid #ddd;
            font-size: 0.9rem;
            color: #666;
        }
        .logout-form {
            display: inline;
        }
        .toggle-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            margin-right: 1rem;
        }
        @media (max-width: 768px) {
            .layout {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                height: auto;
            }
            .sidebar.collapsed {
                width: 100%;
            }
            .content {
                padding: 0.75rem;
            }
        }
    </style>
</head>
<body x-data="{ sidebarOpen: true }">
    <div class="topbar">
        <div style="display: flex; align-items: center;">
            <button class="toggle-btn" @click="sidebarOpen = !sidebarOpen">
                <i class="fas fa-bars"></i>
            </button>
            <span class="title">EDUCATION QMS</span>
        </div>
        <div>
            <span>{{ Auth::user()->name ?? 'User' }}</span> |
            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit" style="background: none; border: none; color: white; cursor: pointer;">Logout</button>
            </form>
        </div>
    </div>

    <div class="layout">
        <div class="sidebar" :class="sidebarOpen ? '' : 'collapsed'">
            <a href="{{ url('/dashboard') }}"><i class="fas fa-home icon"></i> Dashboard</a>
            <a href="{{ route('programs.index') }}"><i class="fas fa-book icon"></i> Programs</a>
            <a href="{{ route('staff.index') }}"><i class="fas fa-user-tie icon"></i> Education Staff</a>
            <a href="{{ route('standards.index') }}"><i class="fas fa-file-alt icon"></i> Standards</a>
            <a href="{{ route('cqi-projects.index') }}"><i class="fas fa-chart-line icon"></i> CQI Projects</a>
            <a href="{{ route('audits.index', ['type' => 'Internal']) }}"><i class="fas fa-search icon"></i> Internal Audit</a>
            <a href="{{ route('audits.index', ['type' => 'External']) }}"><i class="fas fa-chart-line icon"></i>External Audits</a>
            <a href="#"><i class="fas fa-file icon"></i> Reports</a>
            <a href="#"><i class="fas fa-cog icon"></i> Settings</a>
            <a href="#"><i class="fas fa-user icon"></i> Users</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="width: 100%; background: none; border: none; color: white; padding: 0.75rem 1rem; text-align: left; cursor: pointer;">
                    <i class="fas fa-sign-out icon"></i> Logout
                </button>
            </form>
        </div>

        <div class="content">
            @yield('content')
        </div>
    </div>

    <footer>
        © {{ date('Y') }} Education QMS. All rights reserved.
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>