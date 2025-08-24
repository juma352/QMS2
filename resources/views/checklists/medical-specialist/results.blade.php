@extends('layouts.dashboard')

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
                        <p><strong>Submitted by:</strong> {{ $submission->user->name ?? 'N/A' }}</p>
                        <p><strong>Submission Date:</strong> 
                            @if ($submission->created_at)
                                {{ $submission->created_at->timezone('Africa/Nairobi')->format('l, M d, Y \a\t g:i A') }}
                            @else
                                N/A
                            @endif
                        </p>
                        <p><strong>Status:</strong> <span class="badge bg-{{ $submission->status === 'submitted' ? 'success' : 'warning' }}">{{ ucfirst($submission->status) }}</span></p>
                    </div>

                    <div class="mb-4">
                        <h5>Checklist Steps:</h5>
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
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#duplicateModal">
                        Duplicate
                    </button>
                    @if ($submission->status === 'draft')
                        <a href="{{ route('drafts.index') }}" class="btn btn-secondary">Back to Drafts</a>
                    @else
                        <a href="{{ route('submissions.index') }}" class="btn btn-secondary">Back to Submissions</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Duplicate Modal -->
<div class="modal fade" id="duplicateModal" tabindex="-1" aria-labelledby="duplicateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="duplicateModalLabel">Duplicate Checklist</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="duplicateForm" action="{{ route('checklists.medical-specialist.duplicate', $submission->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="new_program_id" class="form-label">Select Program to Duplicate To:</label>
                        <select class="form-select" id="new_program_id" name="new_program_id" required>
                            <option value="">Select a Program</option>
                            @foreach($programs as $program)
                                <option value="{{ $program->id }}" data-program-name="{{ $program->program_name }}">{{ $program->program_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Confirm Duplicate</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('duplicateForm').addEventListener('submit', function(event) {
    var newProgramSelect = document.getElementById('new_program_id');
    if (newProgramSelect.value === "") {
        alert('Please select a program.');
        event.preventDefault();
        return;
    }
    var selectedOption = newProgramSelect.options[newProgramSelect.selectedIndex];
    var newProgramName = selectedOption.getAttribute('data-program-name');

    @php
        $originalProgramId = $submission->progress->draft_data[1]['programs'] ?? null;
        $originalProgram = $originalProgramId ? \App\Models\Program::find($originalProgramId) : null;
        $originalProgramName = $originalProgram ? $originalProgram->program_name : '';
    @endphp

    var originalProgramName = "{{ $originalProgramName }}";

    if (newProgramName === originalProgramName) {
        if (!confirm('Do you want to duplicate this Audit for the same program?')) {
            event.preventDefault();
        }
    }
});
</script>
@endpush