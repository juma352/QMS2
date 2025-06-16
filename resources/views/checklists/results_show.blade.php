@extends('layouts.dashboard')

@section('title', 'Checklist Results')

@section('content')
<div class="card shadow-sm border-0 rounded-3">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center p-3">
        <h2 class="h5 mb-0 fw-semibold">Results for: {{ $submission->checklist->title }}</h2>
        <div>
            {{-- This route name now correctly points to the method in your ChecklistController --}}
            <a href="{{ route('checklists.results.index') }}" class="btn btn-outline-light btn-sm fw-medium">
                <i class="fas fa-list-ul me-2"></i>My Submissions
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm fw-medium">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="alert alert-info d-flex justify-content-between align-items-center">
            <span>Submitted on: <strong>{{ $submission->created_at->format('F d, Y \a\t h:i A') }}</strong></span>
            <span>Status: <strong class="text-capitalize">{{ $submission->status }}</strong></span>
        </div>

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show my-3" role="alert">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @foreach($submission->answers->groupBy('checklistItem.section') as $section => $answers)
            <fieldset class="mb-4">
                <legend class="h6 fw-bold border-bottom pb-2 mb-3"><i class="fas fa-bookmark me-2"></i>{{ $section }}</legend>
                @foreach($answers as $answer)
                    <div class="mb-3 p-3 bg-light rounded-3">
                        <p class="form-label fw-bold mb-1">{{ $answer->checklistItem->question_text }}</p>
                        <div class="d-flex align-items-center">
                            <strong class="me-2">Your Rating:</strong>
                            <span class="badge bg-primary rounded-pill fs-6 px-2">{{ $answer->rating }} / 7</span>
                        </div>
                        @if($answer->comments)
                            <div class="mt-2">
                                <strong class="d-block">Your Comments:</strong>
                                <p class="text-muted fst-italic mb-0" style="white-space: pre-wrap;">"{{ $answer->comments }}"</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </fieldset>
        @endforeach
    </div>
</div>
@endsection