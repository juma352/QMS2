@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
    {{-- The main H1 title is slightly adjusted for the new layout --}}
    <h1 style="font-weight: 600; font-size: 1.5rem; color: #1f2937;">Welcome to the QMS Dashboard</h1>

    {{-- This section will display success/info messages after actions --}}
    @if(session('message'))
        <div class="alert alert-success alert-dismissible fade show my-3" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif(session('info'))
        <div class="alert alert-info alert-dismissible fade show my-3" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- The grid and card styles are tweaked to be more compact --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; margin-top: 1.5rem;">
        @foreach ($summaryCards as $card)
            <div style="background: {{ $card['color'] }}; color: white; padding: 1.25rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); display: flex; flex-direction: column; justify-content: space-between; font-family: 'Inter', sans-serif;">
                <div style="font-size: 1.8rem; font-weight: 600;">{{ $card['count'] }}</div>
                <div style="margin: 0.5rem 0; font-size: 0.9rem; font-weight: 500;">
                    <i class="fas fa-{{ $card['icon'] }}" style="margin-right: 0.5rem;"></i> {{ $card['label'] }}
                </div>
                <div style="text-align: right; margin-top: 0.5rem;">
                    <a href="{{ $card['route'] }}" style="color: rgba(255,255,255,0.8); text-decoration: none; font-size: 0.8rem; border-bottom: 1px solid rgba(255,255,255,0.4);">View more</a>
                </div>
            </div>
        @endforeach
    </div>

    <h2 style="margin-top: 2.5rem; font-size: 1.25rem; font-weight: 600; color: #1f2937; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.5rem;">Compliance Checklists</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1rem; margin-top: 1.5rem;">
        @forelse ($checklists as $checklist)
            @php
                $submission = $userSubmissions->get($checklist->id);
            @endphp
            <div style="background: white; border-radius: 8px; padding: 1rem 1.25rem; box-shadow: 0 2px 8px rgba(0,0,0,0.06); border-top: 4px solid {{ $submission ? '#00A79D' : '#3b82f6' }}; font-family: 'Inter', sans-serif;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="margin: 0; font-size: 1rem; font-weight: 600; color: #111827;">
                        <i class="fas fa-clipboard-check" style="margin-right: 0.5rem; color: #6b7280;"></i>
                        {{ $checklist->title }}
                    </h3>
                    
                    @if($submission)
                        <span style="background: #00A79D; color: white; padding: 0.2rem 0.6rem; font-size: 0.7rem; border-radius: 50px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">
                            Completed
                        </span>
                    @else
                        <span style="background: #F4A300; color: white; padding: 0.2rem 0.6rem; font-size: 0.7rem; border-radius: 50px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">
                            Pending
                        </span>
                    @endif
                </div>
                <p style="margin: 0.75rem 0 1rem; font-size: 0.85rem; color: #6b7280; line-height: 1.5;">{{ $checklist->description ?? 'Evaluate compliance for this area.' }}</p>
                <div style="display: flex; gap: 0.5rem;">
                    @if($submission)
                        <a href="#" style="flex: 1; background: #e5e7eb; color: #6b7280; padding: 0.45rem; text-align: center; text-decoration: none; border-radius: 6px; font-size: 0.8rem; font-weight: 600; cursor: not-allowed;">Completed</a>
                        <a href="{{ route('checklists.results.show', $submission->id) }}" style="flex: 1; background: #00A79D; color: white; padding: 0.45rem; text-align: center; text-decoration: none; border-radius: 6px; font-size: 0.8rem; font-weight: 600;">View Results</a>
                    @else
                        <a href="{{ route('checklists.show', $checklist->slug) }}" style="flex: 1; background: #3b82f6; color: white; padding: 0.45rem; text-align: center; text-decoration: none; border-radius: 6px; font-size: 0.8rem; font-weight: 600;">Fill Checklist</a>
                        <a href="#" style="flex: 1; background: #e5e7eb; color: #6b7280; padding: 0.45rem; text-align: center; text-decoration: none; border-radius: 6px; font-size: 0.8rem; font-weight: 600; cursor: not-allowed;">View Results</a>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-12">
                 <p class="text-muted">No checklists have been configured. Please run the ChecklistSeeder.</p>
            </div>
        @endforelse
    </div>
@endsection