<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'QMS Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        :root {
            --primary-color: #3b82f6; /* A modern, slightly softer blue */
            --primary-hover: #2563eb;
            --sidebar-bg: #111827; /* A very dark gray, softer than pure black */
            --sidebar-link-hover: #1f2937;
            --content-bg: #f3f4f6;
            --text-light: #e5e7eb;
            --text-dark: #374151;
            --border-color: #e5e7eb;
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

        .layout { display: flex; flex-direction: row; }
        .content-wrapper { flex: 1; min-height: 100vh; display: flex; flex-direction: column; }
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
            position: sticky; top: 0; z-index: 1000;
        }
        .topbar .title { font-size: 1.1rem; font-weight: 600; color: var(--text-dark); }
        .toggle-btn { background: none; border: none; color: #6b7280; font-size: 1.25rem; cursor: pointer; margin-right: 1.5rem; transition: color 0.3s ease, transform 0.3s ease; }
        .toggle-btn:hover { color: var(--primary-color); }
        .user-menu { display: flex; align-items: center; gap: 0.75rem; font-size: 0.9rem; font-weight: 500; }
        .logout-btn { background: none; border: none; color: var(--text-dark); cursor: pointer; font-weight: 500; font-size: 0.9rem; transition: color 0.3s ease; }
        .logout-btn:hover { color: var(--primary-color); }

        /* Sidebar */
        .sidebar {
            background: var(--sidebar-bg);
            width: 250px;
            min-height: 100vh;
            padding: 1rem 0;
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
            padding: 0.8rem 1.5rem;
            font-size: 0.9rem;
            font-weight: 500;
            transition: background-color 0.2s, color 0.2s;
            white-space: nowrap;
        }
        .sidebar-link:hover { background: var(--sidebar-link-hover); color: #ffffff; }
        .sidebar-link.active { background: var(--primary-color); color: #ffffff; font-weight: 600; }
        .sidebar-link .icon { font-size: 1rem; width: 24px; text-align: center; margin-right: 1rem; transition: margin-right 0.3s ease; }
        .sidebar-link .link-text { opacity: 1; transition: opacity 0.2s ease; }

        /* Collapsed Sidebar State */
        .sidebar.collapsed { width: 80px; }
        .sidebar.collapsed .sidebar-link { justify-content: center; }
        .sidebar.collapsed .icon { margin-right: 0; }
        .sidebar.collapsed .link-text { opacity: 0; width: 0; overflow: hidden; }

        /* Footer */
        footer { text-align: center; padding: 1.5rem; background-color: #ffffff; border-top: 1px solid var(--border-color); font-size: 0.85rem; color: #6b7280; }

        /* Responsive */
        @media (max-width: 768px) {
            body { font-size: 14px; }
            .toggle-btn { display: none; }
            .layout { flex-direction: column; }
            .sidebar {
                width: 100%;
                min-height: auto;
                position: sticky; top: 0; z-index: 1001;
                flex-direction: row;
                overflow-x: auto;
                padding: 0.5rem;
            }
            .sidebar.collapsed { width: 100%; }
            .sidebar-link { flex-direction: column; padding: 0.6rem 0.5rem; font-size: 0.7rem; border-radius: 6px; }
            .sidebar-link .icon { margin: 0 0 0.25rem 0; }
            .main-content { padding: 1rem; }
            .topbar { display: none; } /* Hide topbar on mobile, user info can be moved if needed */
        }
    </style>
</head>
<body x-data="{ sidebarOpen: window.innerWidth > 768 }">
    <div class="layout">
        <div class="sidebar" :class="sidebarOpen ? '' : 'collapsed'">
            <nav class="sidebar-nav">
                {{-- The active class is applied by checking the current URL path --}}
                <a href="{{ url('/dashboard') }}" class="sidebar-link {{ request()->is('dashboard*') ? 'active' : '' }}">
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
                {{-- RESTORED MENU ITEMS --}}
                <a href="#" class="sidebar-link">
                    <i class="fas fa-chart-pie icon"></i><span class="link-text">Reports</span>
                </a>
                <a href="#" class="sidebar-link">
                    <i class="fas fa-users icon"></i><span class="link-text">Users</span>
                </a>
                <a href="#" class="sidebar-link">
                    <i class="fas fa-cog icon"></i><span class="link-text">Settings</span>
                </a>
            </nav>
            {{-- Logout form at the bottom of the sidebar --}}
            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="sidebar-link" style="width: 100%; border: none; background: none; cursor: pointer;">
                    <i class="fas fa-sign-out-alt icon"></i><span class="link-text">Logout</span>
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
    
    {{-- This allows child pages to push custom scripts here --}}
    @yield('scripts')
</body>
</html>