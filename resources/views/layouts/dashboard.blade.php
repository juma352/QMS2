<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'QMS Dashboard') - Education QMS</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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
            background-color: var(--content-bg);
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
            background-color: #ffffff;
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
        .topbar .page-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-dark);
        }
        .toggle-btn {
            background: none;
            border: none;
            color: var(--text-dark);
            font-size: 1.3rem;
            cursor: pointer;
            margin-right: 1.5rem;
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
            background-color: var(--sidebar-bg);
            width: 260px;
            padding: 1.5rem 0;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }
        .sidebar-header {
            padding: 0 1.75rem 1.5rem 1.75rem;
            color: white;
            font-weight: 700;
            font-size: 1.3rem;
            white-space: nowrap;
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
            white-space: nowrap;
        }
        .sidebar-link:hover {
            background-color: var(--sidebar-link-hover);
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
            transition: margin 0.3s ease;
        }
        .sidebar-link .link-text { transition: opacity 0.3s ease; }
        .sidebar-logout {
            margin-top: auto;
            padding: 0.5rem 1.75rem;
        }
        .sidebar-logout button {
            width: 100%;
            background: none;
            border: none;
            padding: 0;
            margin: 0;
            cursor: pointer;
        }
        .sidebar-logout button:hover .sidebar-link {
            background-color: #be123c;
            transform: translateX(5px);
        }

        /* Collapsed Sidebar */
        .sidebar.collapsed { width: 80px; }
        .sidebar.collapsed .sidebar-header { padding-left: 0; padding-right: 0; text-align: center; font-size: 1rem;}
        .sidebar.collapsed .sidebar-header .full-text { display: none; }
        .sidebar.collapsed .sidebar-link { justify-content: center; padding-left: 0; padding-right: 0; }
        .sidebar.collapsed .icon { margin-right: 0; }
        .sidebar.collapsed .link-text { opacity: 0; width: 0; overflow: hidden; }
        .sidebar.collapsed .sidebar-logout { padding: 0.5rem 0;}

        /* Footer */
        footer {
            text-align: center;
            padding: 1.5rem;
            background-color: #ffffff;
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
                position: fixed;
                left: -260px;
                top: 0;
                height: 100vh;
                z-index: 1002;
                transition: left 0.3s ease;
            }
            .sidebar.open { left: 0; }
            .sidebar.collapsed { width: 260px; /* No collapsed state on mobile */ }
            .sidebar.collapsed .link-text { opacity: 1; width: auto; }
            .sidebar.collapsed .icon { margin-right: 1rem; }
            .main-content { padding: 1rem; }
            .topbar { padding: 0 1rem; height: 60px; }
            .logout-form { display: none; }
            .page-title { font-size: 1rem; }
        }
    </style>
</head>
<body x-data="{ sidebarOpen: window.innerWidth > 768 }" @resize.window="sidebarOpen = window.innerWidth > 768">
<div class="layout">
    {{-- Use x-show on mobile to keep it in the DOM but hidden --}}
    <aside class="sidebar" :class="sidebarOpen ? 'open' : 'collapsed'">
        <div class="sidebar-header">
            <span class="full-text">Education QMS</span>
            <span x-show="!sidebarOpen" title="Education QMS">QMS</span>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->is('dashboard*') ? 'active' : '' }}" title="Dashboard">
                <i class="fas fa-home icon"></i><span class="link-text">Dashboard</span>
            </a>
            <a href="{{ route('programs.index') }}" class="sidebar-link {{ request()->is('programs*') ? 'active' : '' }}" title="Programs">
                <i class="fas fa-book icon"></i><span class="link-text">Programs</span>
            </a>
            <a href="{{ route('staff.index') }}" class="sidebar-link {{ request()->is('staff*') ? 'active' : '' }}" title="Education Staff">
                <i class="fas fa-user-tie icon"></i><span class="link-text">Education Staff</span>
            </a>
            <a href="{{ route('standards.index') }}" class="sidebar-link {{ request()->is('standards*') ? 'active' : '' }}" title="Standards">
                <i class="fas fa-file-alt icon"></i><span class="link-text">Standards</span>
            </a>
            <a href="{{ route('cqi_projects.index') }}" class="sidebar-link {{ request()->is('cqi-projects*') ? 'active' : '' }}" title="CQI Projects">
                <i class="fas fa-chart-line icon"></i><span class="link-text">CQI Projects</span>
            </a>
            <a href="{{ route('audits.index', ['type' => 'Internal']) }}" class="sidebar-link {{ request()->is('audits*') && request('type') == 'Internal' ? 'active' : '' }}" title="Internal Audit">
                <i class="fas fa-search icon"></i><span class="link-text">Internal Audit</span>
            </a>
            <a href="{{ route('audits.index', ['type' => 'External']) }}" class="sidebar-link {{ request()->is('audits*') && request('type') == 'External' ? 'active' : '' }}" title="External Audits">
                <i class="fas fa-building icon"></i><span class="link-text">External Audits</span>
            </a>
            <a href="{{ route('checklists.dynamic_index') }}" class="sidebar-link {{ request()->is('checklists*') ? 'active' : '' }}" title="Checklists">
                <i class="fas fa-clipboard icon"></i><span class="link-text">Checklists</span>
            </a>
            <a href="{{ route('submissions.index') }}" class="sidebar-link {{ request()->is('submissions*') ? 'active' : '' }}" title="My Submissions">
                <i class="fas fa-clipboard-check icon"></i><span class="link-text">My Submissions</span>
            </a>
            <a href="{{ route('reports.index') }}" class="sidebar-link {{ request()->is('reports*') ? 'active' : '' }}" title="Reports">
                <i class="fas fa-chart-pie icon"></i><span class="link-text">Reports</span>
            </a>

            @auth
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->is('users*') ? 'active' : '' }}" title="Users">
                        <i class="fas fa-users-cog icon"></i><span class="link-text">Users</span>
                    </a>
                @endif
            @endauth

            <a href="#" class="sidebar-link" title="Settings">
                <i class="fas fa-cog icon"></i><span class="link-text">Settings</span>
            </a>
        </nav>
        <div class="sidebar-logout">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Logout">
                        <span class="sidebar-link">
                            <i class="fas fa-sign-out-alt icon"></i>
                            <span class="link-text">Logout</span>
                        </span>
                </button>
            </form>
        </div>
    </aside>

    <div class="content-wrapper">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="toggle-btn" @click="sidebarOpen = !sidebarOpen" :title="sidebarOpen ? 'Collapse Sidebar' : 'Expand Sidebar'">
                    <i class="fas fa-bars"></i>
                </button>
                <span class="page-title">@yield('title', 'Dashboard')</span>
            </div>
            @auth
                <div class="user-menu">
                    <span>{{ Auth::user()->name }}</span>
                    <span class="text-muted d-none d-md-inline">|</span>
                    <form method="POST" action="{{ route('logout') }}" class="d-none d-md-inline">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </div>
            @endauth
        </header>

        <main class="main-content">
            @yield('content')
        </main>

        <footer>
            &copy; {{ date('Y') }} AIC Kijabe Hospital - Education QMS. All rights reserved.
        </footer>
    </div>
</div>

<!-- Global Date Modal for Reports -->
<div class="modal fade" id="dateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #6d28d9, #a855f7); color: #ffffff; border-bottom: none;">
                <h5 class="modal-title" id="modalLabel">Generate Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="dateForm" method="GET" action="">
                    <div class="mb-3">
                        <label class="form-label">From</label>
                        <input type="date" name="from" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">To</label>
                        <input type="date" name="to" class="form-control" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitForm()" style="background: #14b8a6; border: none;">Run</button>
            </div>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Inline the openModal and submitForm functions directly --}}
    <script>
        // Global openModal function for reports
        window.openModal = function(name, route) {
            const modalLabel = document.getElementById('modalLabel');
            const dateForm = document.getElementById('dateForm');
            
            if (modalLabel && dateForm) {
                modalLabel.textContent = `Generate ${name}`;
                dateForm.action = route;
                
                // Show the modal
                const dateModal = new bootstrap.Modal(document.getElementById('dateModal'));
                dateModal.show();
            } else {
                console.error('Modal elements not found. Make sure the modal HTML is included.');
            }
        };

        // Global submitForm function for reports
        window.submitForm = function() {
            const dateForm = document.getElementById('dateForm');
            if (dateForm) {
                const from = dateForm.querySelector('input[name="from"]').value;
                const to = dateForm.querySelector('input[name="to"]').value;
                
                if (from && to) {
                    dateForm.submit();
                } else {
                    alert('Please select both "From" and "To" dates.');
                }
            }
        };
    </script>

    {{-- CORRECTED: @stack allows multiple scripts to be pushed from child views --}}
    @stack('scripts')
</body>
</html>
