@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
    <h1 style="font-weight: 600; font-size: 1.6rem;">Welcome to the QMS Dashboard</h1>

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

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; margin-top: 2rem;">
        @foreach ($summaryCards as $card)
            <div style="background: {{ $card['color'] }}; color: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.08); display: flex; flex-direction: column; justify-content: space-between; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                <div style="font-size: 2rem; font-weight: 700;">{{ $card['count'] }}</div>
                <div style="margin: 0.5rem 0; font-size: 1rem;">
                    <i class="fas fa-{{ $card['icon'] }}"></i> {{ $card['label'] }}
                </div>
                <div style="text-align: right;">
                    <a href="{{ $card['route'] }}" style="color: white; text-decoration: underline; font-size: 0.85rem;">View more</a>
                </div>
            </div>
        @endforeach
    </div>

    <h2 style="margin-top: 3rem; font-size: 1.3rem; font-weight: 600;">Compliance Checklists</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 1.2rem; margin-top: 1rem;">
        @forelse ($checklists as $checklist)
            @php
                // Check if a submission exists for this specific checklist for the current user
                $submission = $userSubmissions->get($checklist->id);
            @endphp
            <div style="background: white; border-radius: 10px; padding: 1rem 1.25rem; box-shadow: 0 2px 6px rgba(0,0,0,0.08); border-top: 4px solid {{ $submission ? '#00A79D' : '#159ed5' }}; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="margin: 0; font-size: 1.05rem;">
                        <i class="fas fa-clipboard-check" style="margin-right: 0.5rem;"></i>
                        {{ $checklist->title }}
                    </h3>
                    
                    @if($submission)
                        <span style="background: #00A79D; color: white; padding: 0.25rem 0.6rem; font-size: 0.75rem; border-radius: 6px;">
                            Completed
                        </span>
                    @else
                        <span style="background: orange; color: white; padding: 0.25rem 0.6rem; font-size: 0.75rem; border-radius: 6px;">
                            Pending
                        </span>
                    @endif
                </div>
                <p style="margin: 0.5rem 0 1rem; font-size: 0.9rem;">{{ $checklist->description ?? 'Evaluate compliance for this area.' }}</p>
                <div style="display: flex; gap: 0.5rem;">

                    @if($submission)
                        {{-- If completed, disable "Fill" button and activate "View Results" button --}}
                        <a href="{{ route('checklists.show', $checklist->slug) }}" style="flex: 1; background: #6c757d; opacity: 0.6; cursor: not-allowed; color: white; padding: 0.45rem; text-align: center; text-decoration: none; border-radius: 6px; font-size: 0.85rem; font-weight: 600;">Completed</a>
                        
                        {{-- THE KEY CHANGE IS HERE: The route name is now checklists.results.show --}}
                        <a href="{{ route('checklists.results.show', $submission->id) }}" style="flex: 1; background: #00A79D; color: white; padding: 0.45rem; text-align: center; text-decoration: none; border-radius: 6px; font-size: 0.85rem; font-weight: 600;">View Results</a>
                    @else
                        {{-- If pending, activate "Fill" button and disable "View Results" --}}
                        <a href="{{ route('checklists.show', $checklist->slug) }}" style="flex: 1; background: #159ed5; color: white; padding: 0.45rem; text-align: center; text-decoration: none; border-radius: 6px; font-size: 0.85rem; font-weight: 600;">Fill Checklist</a>
                        <a href="#" style="flex: 1; background: #6c757d; opacity: 0.6; cursor: not-allowed; color: white; padding: 0.45rem; text-align: center; text-decoration: none; border-radius: 6px; font-size: 0.85rem; font-weight: 600;">View Results</a>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-muted">No checklists have been configured. Please run the ChecklistSeeder.</p>
        @endforelse
    </div>
@endsection