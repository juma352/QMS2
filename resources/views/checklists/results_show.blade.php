@extends('layouts.dashboard')

@section('title', 'Checklist Results')

@section('content')
    <style>
        .results-container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            max-width: 900px;
            margin: 2rem auto;
        }
        .results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e9ecef;
        }
        .results-header h2 {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 600;
        }
        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.2rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease-in-out;
        }
        .submission-info {
            display: flex;
            gap: 2rem;
            margin-bottom: 1.5rem;
            color: #6c757d;
        }
        .section-fieldset {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        .section-legend {
            font-size: 1.25rem;
            font-weight: 600;
            padding: 0 0.75rem;
            width: auto;
        }
        .question-block {
            padding: 1.5rem 0;
            border-bottom: 1px solid #e9ecef;
        }
        .question-block:last-child {
            border-bottom: none;
        }
        .question-text {
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 1rem;
        }
        .rating-scale {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .rating-scale-label {
            font-size: 0.9rem;
            color: #495057;
            flex-shrink: 0;
        }
        /* --- Style for horizontal radio buttons --- */
        .radio-group {
            display: flex;
            flex-grow: 1;
            justify-content: space-around;
            align-items: center;
        }
        .radio-item {
            display: flex;
            flex-direction: column; /* Stacks radio and label vertically */
            align-items: center;
            gap: 0.25rem; /* Space between radio and number */
        }
        .comments-block {
            margin-top: 1rem;
        }
    </style>

    <div class="results-container">
        <header class="results-header">
            <h2>Review Submission: {{ $submission->checklist->title }}</h2>
            <div class="flex-shrink-0">
                <a href="{{ route('checklists.results.index') }}" class="btn-action btn-outline-dark">
                    <i class="fas fa-list-ul"></i>My Submissions
                </a>
                <a href="{{ route('dashboard') }}" class="btn-action btn-secondary">
                    <i class="fas fa-arrow-left"></i>Back to Dashboard
                </a>
            </div>
        </header>

        <div class="submission-info">
            <span>Submitted on: <strong>{{ $submission->created_at->format('F d, Y \a\t h:i A') }}</strong></span>
            <span>Department: <strong class="text-info">{{ $submission->department_name ?? 'N/A' }}</strong></span>
            <span>Status: <strong class="text-capitalize">{{ $submission->status }}</strong></span>
        </div>

        @if(session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        <form method="POST" action="{{ route('checklists.results.update', $submission->id) }}">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="department_name" class="form-label fw-semibold">Department Name</label>
                <input type="text" name="department_name" id="department_name" class="form-control" value="{{ $submission->department_name }}" required>
                @error('department_name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            @foreach($submission->answers->groupBy('checklistItem.section') as $section => $answers)
                <fieldset class="section-fieldset">
                    <legend class="section-legend">{{ $section }}</legend>
                    @foreach($answers as $answer)
                        <div class="question-block">
                            <p class="question-text">{{ $loop->parent->iteration }}.{{ $loop->iteration }} 
                                @if($answer->checklistItem->question)
                                    {{ $answer->checklistItem->question->question_text }}
                                @else
                                    {{ $answer->checklistItem->question_text }}
                                @endif
                            </p>

                            <div class="rating-scale">
                                <span class="rating-scale-label">Strongly Disagree</span>
                                <div class="radio-group">
                                    @for ($i = 1; $i <= 7; $i++)
                                        <div class="radio-item">
                                            <label class="form-check-label small">{{ $i }}</label>
                                            <input type="radio" class="form-check-input"
                                                   id="rating_{{ $answer->checklist_item_id }}_{{ $i }}"
                                                   name="ratings[{{ $answer->checklist_item_id }}]"
                                                   value="{{ $i }}"
                                                   @if($i === $answer->rating) checked @endif
                                                   required>
                                        </div>
                                    @endfor
                                </div>
                                <span class="rating-scale-label">Strongly Agree</span>
                            </div>

                            <div class="comments-block">
                                <label for="comments_{{ $answer->checklist_item_id }}" class="form-label small fw-bold">Your Comments:</label>
                                <textarea class="form-control" rows="3"
                                          id="comments_{{ $answer->checklist_item_id }}"
                                          name="comments[{{ $answer->checklist_item_id }}]">{{ $answer->comments }}</textarea>
                            </div>
                        </div>
                    @endforeach
                </fieldset>
            @endforeach
            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn-action btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
                <a href="{{ route('checklists.results.print', $submission->id) }}" target="_blank" class="btn-action btn-secondary ms-2">
                    <i class="fas fa-print"></i> Print Results
                </a>
            </div>
        </form>
    </div>
@endsection
