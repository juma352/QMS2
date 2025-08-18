@extends('layouts.app')

@section('title', 'Medical Specialist Checklist Results')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h1 class="h2">Medical Specialist Checklist Results</h1>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <p><strong>Submitted by:</strong> {{ $submission->user->name }}</p>
                        <p><strong>Submission Date:</strong> {{ $submission->submitted_at->format('F j, Y, g:i a') }}</p>
                        <p><strong>Status:</strong> <span class="badge bg-{{ $submission->status === 'submitted' ? 'success' : 'warning' }}">{{ ucfirst($submission->status) }}</span></p>
                    </div>

                    @foreach($steps as $step => $stepTitle)
                        <div class="accordion mb-3" id="accordionStep{{ $step }}">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingStep{{ $step }}">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseStep{{ $step }}" aria-expanded="true" aria-controls="collapseStep{{ $step }}">
                                        <strong>Step {{ $step }}: {{ $stepTitle }}</strong>
                                    </button>
                                </h2>
                                <div id="collapseStep{{ $step }}" class="accordion-collapse collapse show" aria-labelledby="headingStep{{ $step }}" data-bs-parent="#accordionStep{{ $step }}">
                                    <div class="accordion-body">
                                        @if(isset($allQuestions[$step]))
                                            <ul class="list-group">
                                                @foreach($allQuestions[$step] as $question)
                                                    <li class="list-group-item">
                                                        <h5 class="mb-2">{{ $question['label'] }}</h5>
                                                        @php
                                                            $submissionAnswer = $answersMap[(string)$question['id']] ?? null;
                                                            $answerValue = $submissionAnswer ? $submissionAnswer->value : 'Not answered';
                                                            // Decode JSON if the original value was an array
                                                            if ($submissionAnswer && $question['type'] === 'select' && is_string($answerValue)) {
                                                                $decodedValue = json_decode($answerValue, true);
                                                                if (is_array($decodedValue)) {
                                                                    $answerValue = implode(', ', $decodedValue);
                                                                }
                                                            }

                                                            $uploadPath = $progress->draft_data[$step][(string)$question['id'] . '_upload'] ?? null; // Still using draft_data for uploads
                                                        @endphp
                                                        <p class="mb-1"><strong>Answer:</strong></p>
                                                        <div class="p-2 bg-light border rounded">
                                                            {{ $answerValue }}
                                                        </div>

                                                        @if($uploadPath)
                                                            <p class="mt-2 mb-1"><strong>Uploaded Document:</strong></p>
                                                            <a href="{{ asset('storage/' . $uploadPath) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-file-alt me-1"></i> View Document
                                                            </a>
                                                        @elseif(isset($question['requires_upload']) && $question['requires_upload'])
                                                            <p class="mt-2 mb-1 text-muted">No document uploaded.</p>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p>No questions found for this step.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('submissions.index') }}" class="btn btn-secondary">Back to Submissions</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
