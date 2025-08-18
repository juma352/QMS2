@extends('layouts.dashboard')

@section('title', 'Checklists')

@section('content')
    <style>
        :root {
            --primary-color: #4f46e5;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-500: #64748b;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #111827;
        }

        .checklist-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .checklist-title-wrapper h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gray-900);
            margin: 0;
        }

        .checklist-stats {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .stat-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .stat-badge.completed {
            background-color: #dcfce7;
            color: #166534;
        }

        .stat-badge.pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .checklist-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 1.5rem;
        }

        .checklist-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 1.5rem;
            transition: all 0.3s ease;
            border: 1px solid var(--gray-200);
        }

        .checklist-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
            border-color: var(--primary-color);
        }

        .checklist-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .checklist-card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0;
        }

        .checklist-status {
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-completed {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .checklist-description {
            color: var(--gray-600);
            margin-bottom: 1rem;
            line-height: 1.5;
        }

        .checklist-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            color: var(--gray-500);
        }

        .checklist-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-checklist {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary-checklist {
            background-color: var(--primary-color);
            color: white;
            border: none;
        }

        .btn-primary-checklist:hover {
            background-color: #4338ca;
            color: white;
        }

        .btn-outline-checklist {
            background-color: transparent;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }

        .btn-outline-checklist:hover {
            background-color: var(--primary-color);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--gray-400);
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            color: var(--gray-700);
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--gray-500);
            margin-bottom: 1.5rem;
        }

        .progress-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .progress-bar-custom {
            height: 8px;
            border-radius: 4px;
            background-color: var(--gray-200);
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--success-color));
            transition: width 0.3s ease;
        }

        @media (max-width: 768px) {
            .checklist-grid {
                grid-template-columns: 1fr;
            }
            
            .checklist-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>

    <div class="checklist-header">
        <div class="checklist-title-wrapper">
            <h1>Your Assigned Checklists</h1>
            <p class="text-muted mb-0">Manage and complete your quality management checklists</p>
        </div>
        
        @php
            $total = $completedChecklists + $pendingChecklists;
            $completionRate = $total > 0 ? ($completedChecklists / $total) * 100 : 0;
        @endphp
        
        <div class="checklist-stats">
            <div class="stat-badge completed">
                <i class="fas fa-check-circle me-1"></i> {{ $completedChecklists }} Completed
            </div>
            <div class="stat-badge pending">
                <i class="fas fa-clock me-1"></i> {{ $pendingChecklists }} Pending
            </div>
        </div>
    </div>

    @if($total > 0)
        <div class="progress-section">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-semibold">Overall Progress</span>
                <span class="text-muted">{{ round($completionRate) }}% Complete</span>
            </div>
            <div class="progress-bar-custom">
                <div class="progress-fill" style="width: {{ $completionRate }}%"></div>
            </div>
        </div>
    @endif

    @if($checklists->isEmpty())
        <div class="empty-state">
            <i class="fas fa-clipboard-check"></i>
            <h3>No Checklists Assigned</h3>
            <p>You don't have any checklists assigned to you at the moment.</p>
            <a href="{{ route('dashboard') }}" class="btn btn-primary-checklist">
                <i class="fas fa-home me-1"></i> Back to Dashboard
            </a>
        </div>
    @else
        <div class="checklist-grid">
            @foreach ($checklists as $checklist)
                @php
                    $submissionsForChecklist = $userSubmissions->filter(function ($submission) use ($checklist) {
                        return $submission->checklist_id === $checklist->id;
                    });
                    $isCompleted = $submissionsForChecklist->isNotEmpty();
                @endphp
                
                <div class="checklist-card">
                    <div class="checklist-card-header">
                        <h3 class="checklist-card-title">{{ $checklist->title }}</h3>
                        <span class="checklist-status {{ $isCompleted ? 'status-completed' : 'status-pending' }}">
                            {{ $isCompleted ? 'Completed' : 'Pending' }}
                        </span>
                    </div>
                    
                    <p class="checklist-description">
                        {{ $checklist->description ?? 'No description provided for this checklist.' }}
                    </p>
                    
                    <div class="checklist-meta">
                        <span>
                            <i class="fas fa-calendar me-1"></i>
                            Created {{ $checklist->created_at->diffForHumans() }}
                        </span>
                        <span>
                            <i class="fas fa-tasks me-1"></i>
                            {{ $submissionsForChecklist->count() }} submission(s)
                        </span>
                    </div>
                    
                    <div class="checklist-actions">
                        @if($isCompleted)
                            <a href="{{ route('checklists.results.index') }}" class="btn btn-outline-checklist">
                                <i class="fas fa-eye me-1"></i> View Results
                            </a>
                        @endif
                        <a href="{{ route('checklists.show', $checklist->slug) }}" class="btn btn-primary-checklist">
                            <i class="fas fa-plus me-1"></i> {{ $isCompleted ? 'New Submission' : 'Start Checklist' }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
