@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
    {{-- Personalized Header --}}
    <h1 style="font-weight: 700; font-size: 1.8rem; color: #1a202c; margin-bottom: 1rem;">
        {{ $greeting }}, {{ $userName }}!
    </h1>
    <p style="margin-top: -0.75rem; margin-bottom: 1.5rem; font-size: 1rem; color: #4a5568;">
        Here is your quality management overview for Thursday, June 19, 2025.
    </p>

    {{-- Alert messages --}}
    @if(session('message'))
        <div class="alert alert-success alert-dismissible fade show my-3" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Top Row: 5 Main Stat Cards --}}
    <div style="display: flex; flex-wrap: wrap; gap: 1rem; margin: 2rem 0 1rem;">
        @foreach ($topRowCards as $widget)
            <a href="{{ $widget['route'] }}" style="text-decoration: none; flex: 1; min-width: 170px; display: flex;">
                <div style="background: {{ $widget['color'] }}; color: white; padding: 1.25rem; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); display: flex; flex-direction: column; width: 100%; transition: all 0.3s ease;"
                     onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 15px rgba(0,0,0,0.2)';" 
                     onmouseout="this.style.transform=''; this.style.boxShadow='0 4px 10px rgba(0,0,0,0.1)';">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;"><i class="fas fa-{{ $widget['icon'] }}" style="font-size: 1.5rem; opacity: 0.7;"></i><span style="font-size: 2.25rem; font-weight: 800; line-height: 1;">{{ $widget['value'] }}</span></div>
                    <div style="margin-top: auto; font-size: 1rem; font-weight: 600;">{{ $widget['label'] }}</div>
                </div>
            </a>
        @endforeach
    </div>
    
    {{-- Second Row: 3 Info Cards --}}
    <div style="display: flex; flex-wrap: wrap; gap: 1rem; margin: 1rem 0 3rem;">
         @foreach ($secondRowCards as $widget)
            <a href="{{ $widget['route'] }}" style="text-decoration: none; flex: 1; min-width: 260px; display: flex;">
                <div style="background: {{ $widget['color'] }}; color: white; padding: 1.25rem; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); display: flex; flex-direction: column; width: 100%; transition: all 0.3s ease; cursor: {{ $widget['route'] === '#' ? 'default' : 'pointer' }};"
                     @if($widget['route'] !== '#')
                         onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 15px rgba(0,0,0,0.2)';" 
                         onmouseout="this.style.transform=''; this.style.boxShadow='0 4px 10px rgba(0,0,0,0.1)';"
                     @endif>
                    @if($widget['type'] === 'info')
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;"><i class="fas fa-{{ $widget['icon'] }}" style="font-size: 1.5rem; opacity: 0.7;"></i><span style="font-size: 2.25rem; font-weight: 800; line-height: 1;">{{ $widget['value'] }}</span></div><div style="margin-top: auto;"><div style="font-size: 1rem; font-weight: 600;">{{ $widget['label'] }}</div><div style="font-size: 0.8rem; opacity: 0.9;">{{ $widget['text'] }}</div></div>
                    @elseif($widget['type'] === 'info_text_only')
                        <div style="margin-bottom: 0.5rem;"><i class="fas fa-{{ $widget['icon'] }}" style="font-size: 1.5rem; opacity: 0.7;"></i></div><div style="margin-top: auto;"><div style="font-size: 1rem; font-weight: 600; margin-bottom: 0.25rem;">{{ $widget['label'] }}</div><div style="font-size: 0.85rem; opacity: 0.9; line-height: 1.4;">{{ $widget['text'] }}</div></div>
                    @endif
                </div>
            </a>
        @endforeach
    </div>


    {{-- Checklist Section (Unchanged, as requested) --}}
    <div id="checklists" style="background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); padding: 1.75rem; margin-bottom: 2rem;">
        <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1.5rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid #e2e8f0;">
            <div style="display: flex; align-items: center; gap: 1.5rem;">
                @php
                    $total = $completedChecklists + $pendingChecklists;
                    $completedPercentage = $total > 0 ? ($completedChecklists / $total) * 100 : 0;
                    $strokeDasharray = $total > 0 ? (25.12 * $completedPercentage / 100) . " 25.12" : "0 25.12";
                @endphp
                <div style="position: relative; width: 60px; height: 60px;">
                    <svg viewBox="0 0 10 10" style="width: 100%; height: 100%; transform: rotate(-90deg);">
                        <circle cx="5" cy="5" r="4" fill="none" stroke="#e5e7eb" stroke-width="1.5"></circle>
                        <circle cx="5" cy="5" r="4" fill="none" stroke="#22C55E" stroke-width="1.5" stroke-dasharray="{{ $strokeDasharray }}" stroke-linecap="round"></circle>
                    </svg>
                    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; font-weight: 600; color: #166534;">
                        {{ round($completedPercentage) }}%
                    </div>
                </div>
                <div>
                    <h2 style="font-size: 1.3rem; font-weight: 600; color: #1a202c; margin: 0;">Your Assigned Checklists</h2>
                    <p style="margin: 0.25rem 0 0; font-size: 0.9rem; color: #4a5568;">
                        <span style="font-weight: 500; color: #166534;">{{ $completedChecklists }} Completed</span> &bull; 
                        <span style="color: #854d0e;">{{ $pendingChecklists }} Pending</span>
                    </p>
                </div>
            </div>
        </div>
        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            @if($checklists->isEmpty())
                <div style="text-align: center; padding: 2rem 1rem; color: #718096; background-color: #f8fafc; border-radius: 8px;">
                    <i class="fas fa-clipboard-check" style="font-size: 2.5rem; margin-bottom: 1rem; color: #22C55E;"></i>
                    <h3 style="font-weight: 600; color: #4a5568;">All Clear!</h3>
                    <p style="margin: 0.25rem 0 0; font-size: 0.9rem;">You have no assigned checklists at the moment.</p>
                </div>
            @else
                @foreach ($checklists as $checklist)
                    @php $submission = $userSubmissions->get($checklist->id); @endphp
                    <div style="display: flex; align-items: center; padding: 0.75rem 1rem; border-radius: 8px; transition: background-color 0.2s ease;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='transparent'">
                        <div style="flex-grow: 1; display: flex; align-items: center; gap: 1rem;">
                            <i class="fas {{ $submission ? 'fa-check-circle text-success' : 'fa-circle text-muted' }}" style="font-size: 1.2rem; color: {{ $submission ? '#22C55E' : '#d1d5db' }};"></i>
                            <div>
                                <h4 style="font-size: 1rem; font-weight: 500; color: #1a202c; margin: 0;">{{ $checklist->title }}</h4>
                                <p style="font-size: 0.85rem; color: #4a5568; margin: 0.1rem 0 0;">{{ Str::limit($checklist->description, 70) ?? 'Evaluate compliance for this area.' }}</p>
                            </div>
                        </div>
                        @if ($submission)
                            <a href="{{ route('checklists.results.show', $submission->id) }}" style="text-decoration: none; color: #059669; background-color: #dcfce7; font-size: 0.8rem; font-weight: 500; padding: 0.3rem 0.8rem; border-radius: 9999px; white-space: nowrap;">View Results</a>
                        @else
                            <a href="{{ route('checklists.show', $checklist->slug) }}" style="text-decoration: none; color: white; background-color: #3b82f6; font-size: 0.8rem; font-weight: 500; padding: 0.3rem 0.8rem; border-radius: 9999px; white-space: nowrap; transition: background-color 0.2s ease;" onmouseover="this.style.backgroundColor='#2563eb'" onmouseout="this.style.backgroundColor='#3b82f6'">Start</a>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection