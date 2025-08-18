@extends('layouts.dashboard')

@section('title', 'Submission Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Submission Details</h1>
                <a href="{{ route('submissions.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Results
                </a>
            </div>

            <div class="row">
                <!-- Submission Summary -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Submission Summary</h5>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-5">Submission ID:</dt>
                                <dd class="col-sm-7">#{{ $submission->id }}</dd>
                                
                                <dt class="col-sm-5">Checklist:</dt>
                                <dd class="col-sm-7">{{ $submission->checklist->title ?? 'N/A' }}</dd>
                                
                                <dt class="col-sm-5">Department:</dt>
                                <dd class="col-sm-7">{{ $submission->department_name ?? 'N/A' }}</dd>
                                
                                <dt class="col-sm-5">Submitted By:</dt>
                                <dd class="col-sm-7">{{ $submission->user->name ?? 'Unknown' }}</dd>
                                
                                <dt class="col-sm-5">Submitted At:</dt>
                                <dd class="col-sm-7">{{ $submission->created_at->format('M d, Y H:i') }}</dd>
                                
                                <dt class="col-sm-5">Status:</dt>
                                <dd class="col-sm-7">
                                    <span class="badge badge-{{ $submission->status == 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($submission->status) }}
                                    </span>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Submission Answers -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Submission Answers</h5>
                        </div>
                        <div class="card-body">
                            @if($submission->answers->isEmpty())
                                <div class="text-center py-4">
                                    <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                                    <h6 class="text-muted">No answers found for this submission</h6>
                                </div>
                            @else
                                <div class="accordion" id="answersAccordion">
                                    @php
                                        $groupedAnswers = $submission->answers->groupBy(function($answer) {
                                            return $answer->checklistItem ? $answer->checklistItem->section : 'General';
                                        });
                                    @endphp

                                    @foreach($groupedAnswers as $section => $answers)
                                        <div class="card mb-3">
                                            <div class="card-header bg-light" id="heading{{ Str::slug($section) }}">
                                                <h6 class="mb-0">
                                                    <button class="btn btn-link text-decoration-none text-dark" type="button" data-toggle="collapse" 
                                                            data-target="#collapse{{ Str::slug($section) }}" aria-expanded="true" 
                                                            aria-controls="collapse{{ Str::slug($section) }}">
                                                        <i class="fas fa-chevron-down mr-2"></i>
                                                        {{ $section }}
                                                        <span class="badge badge-secondary ml-2">{{ $answers->count() }}</span>
                                                    </button>
                                                </h6>
                                            </div>

                                            <div id="collapse{{ Str::slug($section) }}" class="collapse show" 
                                                 aria-labelledby="heading{{ Str::slug($section) }}" 
                                                 data-parent="#answersAccordion">
                                                <div class="card-body">
                                                    @foreach($answers as $index => $answer)
                                                        @php
                                                            $ratingValue = $answer->rating;
                                                            $ratingText = $answer->checklistItem ? $answer->checklistItem->getRatingLabel($ratingValue) : 'N/A';

                                                            $badgeColor = 'secondary';
                                                            if ($answer->checklistItem && $answer->checklistItem->rating_type === 'scale_1_5') {
                                                                $badgeColor = $ratingValue >= 4 ? 'success' : ($ratingValue >= 3 ? 'warning' : 'danger');
                                                            } elseif ($answer->checklistItem && $answer->checklistItem->rating_type === 'scale_1_10') {
                                                                $badgeColor = $ratingValue >= 8 ? 'success' : ($ratingValue >= 6 ? 'warning' : 'danger');
                                                            } elseif ($answer->checklistItem && in_array($answer->checklistItem->rating_type, ['yes_no', 'pass_fail'])) {
                                                                $badgeColor = $ratingValue ? 'success' : 'danger';
                                                            }
                                                        @endphp

                                                        <div class="mb-3 p-3 border rounded bg-light">
                                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                                <div class="flex-grow-1">
                                                                    <h6 class="mb-1 font-weight-bold">
                                                                        {{ $index + 1 }}. {{ $answer->checklistItem ? $answer->checklistItem->question_text : 'N/A' }}
                                                                    </h6>
                                                                    <small class="text-muted">
                                                                        Type: {{ ucfirst(str_replace('_', ' ', $answer->checklistItem ? $answer->checklistItem->rating_type : 'N/A')) }}
                                                                    </small>
                                                                </div>
                                                                <div>
                                                                    <span class="badge badge-{{ $badgeColor }}">
                                                                        {{ $ratingText }}
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <div class="mt-2">
                                                                <small class="text-muted">
                                                                    <strong>Rating:</strong> {{ $ratingValue }} — {{ $ratingText }}
                                                                </small>
                                                            </div>

                                                            @if($answer->notes)
                                                                <div class="mt-3 p-3 bg-white rounded border">
                                                                    <strong><i class="fas fa-sticky-note mr-2"></i> Notes:</strong>
                                                                    <p class="mb-0">{{ $answer->notes }}</p>
                                                                </div>
                                                            @endif

                                                            @if($answer->evidence)
                                                                <div class="mt-2">
                                                                    <strong><i class="fas fa-paperclip mr-2"></i> Evidence:</strong>
                                                                    <a href="{{ Storage::url($answer->evidence) }}" target="_blank">View File</a>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Final Comments -->
                    @if(!empty($submission->comments))
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0">Final Comments</h5>
                            </div>
                            <div class="card-body">
                                <p>{{ $submission->comments }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('submissions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Results
                        </a>
                        
                        <div>
                            <a href="{{ route('checklists.dynamic_index', $submission->checklist_id) }}" 
                               class="btn btn-info">
                                <i class="fas fa-file-alt"></i> View Checklist
                            </a>
                            
                            @if($submission->status == 'completed')
                                <a href="{{ route('submissions.print', $submission) }}" 
                                   class="btn btn-outline-primary ml-2" 
                                   target="_blank">
                                    <i class="fas fa-print"></i> Print
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
