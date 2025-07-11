@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
    <style>
        :root {
            --primary-color: #4f46e5;
            --success-color: #10b981;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-500: #64748b;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #111827;
        }

        /* --- HEADER --- */
        .dashboard-header { margin-bottom: 2.5rem; }
        .dashboard-header h1 {
            font-weight: 800;
            font-size: 2.25rem;
            color: var(--gray-900);
        }
        .dashboard-header p {
            color: var(--gray-500);
            font-size: 1.1rem;
        }

        /* --- MORE VIBRANT GRADIENT BACKGROUNDS --- */
        .bg-gradient-1 { background-image: linear-gradient(135deg, #818cf8 0%, #6366f1 100%); } /* Indigo/Violet */
        .bg-gradient-2 { background-image: linear-gradient(135deg, #22d3ee 0%, #0e7490 100%); } /* Bright Cyan/Teal */
        .bg-gradient-3 { background-image: linear-gradient(135deg, #a3e635 0%, #4d7c0f 100%); } /* Vibrant Lime/Green */
        .bg-gradient-4 { background-image: linear-gradient(135deg, #fb923c 0%, #f97316 100%); } /* Bright Orange */
        .bg-gradient-5 { background-image: linear-gradient(135deg, #f472b6 0%, #db2777 100%); } /* Energetic Pink/Rose */


        /* --- STATS CONTAINER --- */
        .stats-container {
            display: flex;
            flex-wrap: nowrap;
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            flex: 1;
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .stat-card::before { /* Enhanced shiny overlay */
            content: '';
            position: absolute;
            top: 0; left: -85%;
            width: 200%; height: 100%;
            background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.25) 50%, rgba(255,255,255,0) 100%);
            transform: skewX(-25deg);
            transition: left 0.75s ease-in-out;
        }
        .stat-card:hover::before {
            left: 60%;
        }

        .stat-card:hover {
            transform: translateY(-6px) scale(1.02);
            box-shadow: 0 20px 30px rgba(0,0,0,0.12);
        }
        .stat-card-content { z-index: 2; position: relative; }
        .stat-card-content .value { font-size: 2.5rem; font-weight: 800; }
        .stat-card-content .label { font-size: 1rem; font-weight: 500; opacity: 0.9; }
        .stat-card-content .icon { font-size: 1.75rem; opacity: 0.8; }

        /* --- CHECKLIST CONTAINER --- */
        .checklist-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            padding: 2rem;
        }
        .checklist-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .checklist-title-wrapper h2 { font-size: 1.5rem; font-weight: 700; color: var(--gray-800); }
        .progress-circle { width: 60px; height: 60px; position: relative; }
        .progress-circle svg { transform: rotate(-90deg); }
        .progress-circle-bg { fill: none; stroke: var(--gray-200); stroke-width: 6; }
        .progress-circle-fill { fill: none; stroke: var(--success-color); stroke-width: 6; stroke-linecap: round; transition: stroke-dasharray 0.6s ease; }
        .progress-text { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; font-weight: 600; color: var(--success-color); }

        /* --- CHECKLIST GRID --- */
        .checklist-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 1.25rem; }
        .checklist-card {
            display: flex; align-items: center;
            padding: 1.25rem 1.5rem;
            border-radius: 12px;
            transition: all 0.2s ease;
            background: var(--gray-50);
            border: 1px solid var(--gray-100);
        }
        .checklist-card:hover { transform: translateY(-3px); border-color: var(--gray-200); box-shadow: 0 5px 15px rgba(0,0,0,0.05); background: white; }
        .checklist-icon { font-size: 1.2rem; margin-right: 1rem; }
        .checklist-details { flex-grow: 1; min-width: 0; }
        .checklist-title { font-size: 1rem; font-weight: 600; color: var(--gray-700); margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .checklist-desc { font-size: 0.8rem; color: var(--gray-500); margin-top: 2px; }
        .btn-action { text-decoration: none; font-size: 0.8rem; font-weight: 500; padding: 0.4rem 0.8rem; border-radius: 6px; transition: all 0.2s ease; display: inline-flex; align-items: center; justify-content: center; }
        .btn-primary { background-color: var(--primary-color); color: white; }
        .btn-primary:hover { background-color: #4338ca; }

        /* --- EMPTY STATE --- */
        .empty-state { text-align: center; padding: 2.5rem 1.5rem; background-color: var(--gray-50); border-radius: 10px; border: 1px dashed var(--gray-200); }
        .empty-state i { font-size: 2.5rem; margin-bottom: 1rem; color: var(--success-color); }
        .empty-state h3 { font-weight: 600; color: var(--gray-700); }
        .empty-state p { color: var(--gray-500); margin: 0; }

        /* --- RESPONSIVE ADJUSTMENTS --- */
        @media (max-width: 1200px) {
            .stats-container { grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); display: grid; }
        }
    </style>

    <div class="dashboard-header">
        <h1>{{ $greeting }}, <span style="color: var(--primary-color);">{{ $userName }}</span>!</h1>
        <p>Your quality management overview for today, {{ \Carbon\Carbon::now()->format('F j, Y') }}.</p>
    </div>

    @if(session('message'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="stats-container">
        @php
            $gradientClasses = ['bg-gradient-1', 'bg-gradient-2', 'bg-gradient-3', 'bg-gradient-4', 'bg-gradient-5'];
        @endphp
        @foreach ($topRowCards as $widget)
            <div class="stat-card {{ $gradientClasses[$loop->index % count($gradientClasses)] }}">
                <div class="stat-card-content d-flex flex-column justify-content-between h-100">
                    <div class="d-flex justify-content-between align-items-start">
                        <i class="fas fa-{{ $widget['icon'] }} icon"></i>
                        <span class="value">{{ $widget['value'] }}</span>
                    </div>
                    <a href="{{ $widget['route'] }}" class="text-white text-decoration-none stretched-link">
                        <div class="label mt-3">{{ $widget['label'] }}</div>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="checklist-container">
        <div class="checklist-header">
            @php
                $total = $completedChecklists + $pendingChecklists;
                $completedPercentage = $total > 0 ? ($completedChecklists / $total) * 100 : 0;
            @endphp
            <div class="d-flex align-items-center gap-3">
                <div class="progress-circle" aria-label="Checklist completion: {{ round($completedPercentage) }}%">
                    <svg viewBox="0 0 36 36">
                        <circle class="progress-circle-bg" cx="18" cy="18" r="15.9155"></circle>
                        <circle class="progress-circle-fill" cx="18" cy="18" r="15.9155" stroke-dasharray="{{ $completedPercentage }} 100"></circle>
                    </svg>
                    <div class="progress-text">{{ round($completedPercentage) }}%</div>
                </div>
                <div class="checklist-title-wrapper">
                    <h2>Your Assigned Checklists</h2>
                </div>
            </div>
            <div class="d-flex gap-4" style="font-size: 0.85rem;">
                <span class="text-success fw-semibold"><i class="fas fa-check-circle me-1"></i> {{ $completedChecklists }} Completed</span>
                <span class="text-warning fw-semibold"><i class="fas fa-circle-notch me-1"></i> {{ $pendingChecklists }} Pending</span>
            </div>
        </div>

        @if($checklists->isEmpty())
            <div class="empty-state">
                <i class="fas fa-clipboard-check"></i>
                <h3>All Clear!</h3>
                <p>You have no assigned checklists at the moment.</p>
            </div>
        @else
            <div class="checklist-grid">
                @foreach ($checklists as $checklist)
                    @php $submission = $userSubmissions->get($checklist->id); @endphp
                    <div class="checklist-card">
                        <i class="fas {{ $submission ? 'fa-check-circle text-success' : 'fa-circle-notch text-muted' }} checklist-icon"></i>
                        <div class="checklist-details">
                            <h4 class="checklist-title">{{ $checklist->title }}</h4>
                            <p class="checklist-desc">{{ Str::limit($checklist->description, 50) ?? 'No description.' }}</p>
                        </div>
                        <div class="ms-auto ps-2">
                            @if ($submission)
                                <a href="{{ route('checklists.results.show', $submission->id) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-chart-bar"></i> Results</a>
                            @else
                                <a href="{{ route('checklists.show', $checklist->slug) }}" class="btn-action btn-primary"><i class="fas fa-play"></i> Start</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
