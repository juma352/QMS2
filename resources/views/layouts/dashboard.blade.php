<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'QMS Dashboard')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        :root {
            --primary: #6d28d9; /* Vibrant purple */
            --secondary: #14b8a6; /* Teal */
            --accent: #f97316; /* Bright orange */
            --text-dark: #1e293b; /* Slate */
            --text-light: #64748b; /* Cool gray */
            --sidebar-bg: #1e293b; /* Dark slate */
            --sidebar-link-hover: #334155; /* Lighter slate */
            --sidebar-link-active: var(--primary);
            --content-bg: #f1f5f9; /* Light slate */
            --border-color: #e2e8f0; /* Light gray */
            --gradient: linear-gradient(135deg, #6d28d9, #a855f7);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: var(--content-bg);
            color: var(--text-dark);
            font-size: 15px;
            font-weight: 400;
        }
        a { text-decoration: none; }
        .layout { display: flex; flex-direction: row; min-height: 100vh; }
        .content-wrapper { flex: 1; display: flex; flex-direction: column; }
        .main-content { flex: 1; padding: 2rem; }
        
        /* Top Bar */
        .topbar {
            background: #ffffff;
            padding: 0 2rem;
            height: 65px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        .topbar .title {
            font-size: 1.2rem;
            font-weight: 600;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .toggle-btn {
            background: none;
            border: none;
            color: var(--text-dark);
            font-size: 1.3rem;
            cursor: pointer;
            transition: color 0.3s ease, transform 0.3s ease;
        }
        .toggle-btn:hover {
            color: var(--primary);
            transform: scale(1.1);
        }
        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 0.95rem;
            font-weight: 500;
        }
        .user-menu span {
            color: var(--text-dark);
        }
        .logout-btn {
            background: var(--gradient);
            color: #ffffff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .logout-btn:hover {
            background: linear-gradient(135deg, #5b21b6, #9333ea);
            transform: translateY(-2px);
        }
        
        /* Sidebar */
        .sidebar {
            background: var(--sidebar-bg);
            width: 260px;
            padding: 1.5rem 0;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }
        .sidebar-nav { flex-grow: 1; }
        .sidebar-link {
            display: flex;
            align-items: center;
            color: var(--text-light);
            padding: 0.9rem 1.75rem;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .sidebar-link:hover {
            background: var(--sidebar-link-hover);
            color: #ffffff;
            transform: translateX(5px);
        }
        .sidebar-link.active {
            background: var(--gradient);
            color: #ffffff;
            font-weight: 600;
        }
        .sidebar-link .icon {
            font-size: 1.1rem;
            width: 28px;
            text-align: center;
            margin-right: 1rem;
        }
        .sidebar-link .link-text { transition: opacity 0.3s ease; }
        .sidebar-logout-btn { background: none; border: none; }
        .sidebar-logout-btn:hover .sidebar-link {
            background: #be123c;
            transform: translateX(5px);
        }
        
        /* Collapsed Sidebar */
        .sidebar.collapsed { width: 80px; }
        .sidebar.collapsed .sidebar-link { justify-content: center; padding: 0.9rem; }
        .sidebar.collapsed .icon { margin-right: 0; }
        .sidebar.collapsed .link-text { opacity: 0; width: 0; overflow: hidden; }
        
        /* Footer */
        footer {
            text-align: center;
            padding: 1.5rem;
            background: #ffffff;
            border-top: 1px solid var(--border-color);
            font-size: 0.9rem;
            color: var(--text-light);
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.05);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            body { font-size: 14px; }
            .layout { flex-direction: column; }
            .sidebar {
                width: 100%;
                min-height: auto;
                position: sticky;
                top: 0;
                z-index: 1001;
                flex-direction: row;
                padding: 0.5rem;
                justify-content: center;
            }
            .sidebar-nav { display: flex; flex-direction: row; gap: 0.5rem; }
            .sidebar.collapsed { width: 100%; }
            .sidebar-link { flex-direction: column; padding: 0.7rem; font-size: 0.75rem; border-radius: 8px; }
            .sidebar-link .icon { margin: 0 0 0.3rem 0; }
            .main-content { padding: 1rem; }
            .topbar { padding: 0 1rem; height: 60px; }
            .logout-form { display: none; }
        }
    </style>
</head>
<body x-data="{ sidebarOpen: window.innerWidth > 768 }">
    <div class="layout">
        <div class="sidebar" :class="sidebarOpen ? '' : 'collapsed'">
            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->is('dashboard*') ? 'active' : '' }}">
                    <i class="fas fa-home icon"></i><span class="link-text">Dashboard</span>
                </a>
                <a href="{{ route('programs.index') }}" class="sidebar-link {{ request()->is('programs*') ? 'active' : '' }}">
                    <i class="fas fa-book icon"></i><span class="link-text">Programs</span>
                </a>
                <a href="{{ route('staff.index') }}" class="sidebar-link {{ request()->is('staff*') ? 'active' : '' }}">
                    <i class="fas fa-user-tie icon"></i><span class="link-text">Education Staff</span>
                </a>
                <a href="{{ route('standards.index') }}" class="sidebar-link {{ request()->is('standards*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt icon"></i><span class="link-text">Standards</span>
                </a>
                <a href="{{ route('cqi-projects.index') }}" class="sidebar-link {{ request()->is('cqi-projects*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line icon"></i><span class="link-text">CQI Projects</span>
                </a>
                <a href="{{ route('audits.index', ['type' => 'Internal']) }}" class="sidebar-link {{ request()->is('audits*') && request('type') == 'Internal' ? 'active' : '' }}">
                    <i class="fas fa-search icon"></i><span class="link-text">Internal Audit</span>
                </a>
                <a href="{{ route('audits.index', ['type' => 'External']) }}" class="sidebar-link {{ request()->is('audits*') && request('type') == 'External' ? 'active' : '' }}">
                    <i class="fas fa-building icon"></i><span class="link-text">External Audits</span>
                </a>
                <a href="{{ route('checklists.results.index') }}" class="sidebar-link {{ request()->is('checklists/results*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-check icon"></i><span class="link-text">My Submissions</span>
                </a>
                <a href="{{ route('reports.index') }}" class="sidebar-link {{ request()->is('reports*') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie icon"></i><span class="link-text">Reports</span>
                </a>
                <a href="#" class="sidebar-link">
                    <i class="fas fa-users icon"></i><span class="link-text">Users</span>
                </a>
                <a href="#" class="sidebar-link">
                    <i class="fas fa-cog icon"></i><span class="link-text">Settings</span>
                </a>
            </nav>
            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="sidebar-logout-btn">
                    <span class="sidebar-link">
                        <i class="fas fa-sign-out-alt icon"></i>
                        <span class="link-text">Logout</span>
                    </span>
                </button>
            </form>
        </div>

        <div class="content-wrapper">
            <div class="topbar">
                <div style="display: flex; align-items: center;">
                    <button class="toggle-btn" @click="sidebarOpen = !sidebarOpen" x-bind:title="sidebarOpen ? 'Collapse Sidebar' : 'Expand Sidebar'">
                        <i class="fas fa-bars"></i>
                    </button>
                    <span class="title">EDUCATION QMS</span>
                </div>
                <div class="user-menu">
                    <span>{{ Auth::user()->name ?? 'User' }}</span>
                    <span class="text-muted">|</span>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </div>
            </div>

            <div class="main-content">
                @yield('content')
            </div>
            
            <footer>
                © {{ date('Y') }} AIC Kijabe Hospital - Education QMS. All rights reserved.
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>