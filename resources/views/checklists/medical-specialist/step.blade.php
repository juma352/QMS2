@extends('layouts.dashboard')

@section('title', 'Step ' . $step . ': ' . $stepTitle)

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- Sidebar: Checklist Progress --}}
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light fw-bold">Checklist Progress</div>
                <div class="card-body p-3">
                    <ul class="list-group list-group-flush">
                        @foreach($steps as $stepNum => $stepLabel)
                           @php
    $isCompleted = isset($progress) && is_object($progress) && property_exists($progress, 'completed_steps') && is_array($progress->completed_steps) && in_array($stepNum, $progress->completed_steps);
    $isCurrent = isset($step) && $step == $stepNum;
    $isClickable = true; // Allow all steps to be clickable
@endphp
                            <li class="list-group-item {{ $isCurrent ? 'bg-primary text-white fw-semibold' : '' }}">
                                @if($isClickable)
                                    <a href="{{ route('checklists.medical-specialist.step', ['submission' => $submission->id, 'step' => $stepNum]) }}"
                                       class="text-decoration-none {{ $isCurrent ? 'text-white' : 'text-dark' }}">
                                        Step {{ $stepNum }}: {{ $stepLabel }}
                                    </a>
                                @else
                                    <span class="text-muted">Step {{ $stepNum }}: {{ $stepLabel }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="col-md-9">
            {{-- Progress Bar --}}
            <div class="mb-3">
                @php
                    $totalSteps = count($steps);
                    $percentage = $totalSteps > 0 ? ($step / $totalSteps) * 100 : 0;
                @endphp
                <div class="progress" style="height: 25px;">
                    <div class="progress-bar bg-success fw-semibold" role="progressbar"
                         style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}"
                         aria-valuemin="0" aria-valuemax="100">
                        {{ round($percentage) }}% Complete
                    </div>
                </div>
            </div>

            {{-- Step Form --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h4 class="mb-0">{{ $stepTitle }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('checklists.medical-specialist.save-step', ['submission' => $submission->id, 'step' => $step]) }}"
                          method="POST" enctype="multipart/form-data">
                        @csrf

                        @foreach($questions as $question)
                            <div class="mb-4">
                                <label for="{{ $question['id'] }}" class="form-label fw-medium">
                                    {{ $question['label'] }}
                                </label>

                                @if($question['type'] === 'text')
                                    <input type="text" class="form-control" id="{{ $question['id'] }}"
                                           name="data[{{ $question['id'] }}]"
                                           value="{{ old('data.' . $question['id'], $stepData[$question['id']] ?? '') }}">
                                @elseif($question['type'] === 'textarea')
                                    <textarea class="form-control" id="{{ $question['id'] }}"
                                              name="data[{{ $question['id'] }}]" rows="3">{{ old('data.' . $question['id'], $stepData[$question['id']] ?? '') }}</textarea>
                                @elseif($question['type'] === 'number')
                                    <input type="number" class="form-control" id="{{ $question['id'] }}"
                                           name="data[{{ $question['id'] }}]"
                                           value="{{ old('data.' . $question['id'], $stepData[$question['id']] ?? '') }}">
                                @elseif($question['type'] === 'select')
                                    <select class="form-select" id="{{ $question['id'] }}"
                                            name="data[{{ $question['id'] }}]">
                                        <option value="">Select an option</option>
                                        @foreach($question['options'] as $option)
                                            <option value="{{ $option }}"
                                                {{ (old('data.' . $question['id'], $stepData[$question['id']] ?? '') == $option) ? 'selected' : '' }}>
                                                {{ $option }}
                                            </option>
                                        @endforeach
                                    </select>
                                @elseif($question['type'] === 'select_dynamic')
                                    <select class="form-select" id="{{ $question['id'] }}"
                                            name="data[{{ $question['id'] }}]">
                                        <option value="">Select an option</option>
                                        @foreach($question['options'] as $id => $name)
                                            <option value="{{ $id }}"
                                                {{ (old('data.' . $question['id'], $stepData[$question['id']] ?? '') == $id) ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif

                                @error('data.' . $question['id'])
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror

                                @if(!empty($question['requires_upload']))
                                    <div class="mt-3">
                                        <label for="{{ $question['id'] }}_upload" class="form-label">Upload File</label>
                                        <input type="file" class="form-control" id="{{ $question['id'] }}_upload"
                                               name="uploads[{{ $question['id'] }}]">
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        {{-- Navigation Buttons --}}
                        <div class="d-flex justify-content-between mt-4">
                            @if($step > 1)
                                <button type="submit" name="action" value="previous" class="btn btn-outline-secondary">
                                    ← Previous
                                </button>
                            @else
                                <div></div>
                            @endif

                            <div class="d-flex gap-2">
                                <button type="submit" name="action" value="save" class="btn btn-outline-info">
                                    💾 Save Draft
                                </button>
                                @if($step < count($steps))
                                    <button type="submit" name="action" value="next" class="btn btn-primary">
                                        Next →
                                    </button>
                                @else
                                    <button type="submit" name="action" value="submit" class="btn btn-success">
                                        ✅ Submit Checklist
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>

                    {{-- Save Message Placeholder --}}
                    <div id="save-message" class="alert mt-3" style="display:none;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let saveTimer;
        const saveMessage = $('#save-message');
        const form = $('form');

        function showSaveMessage(message, type = 'info') {
            saveMessage.removeClass().addClass(`alert alert-${type}`).text(message).fadeIn();
        }

        function hideSaveMessage() {
            saveMessage.fadeOut();
        }

        function saveFormData() {
            showSaveMessage('Saving draft...', 'info');
            const formData = form.serializeArray();
            const data = {};
            formData.forEach(item => {
                if (item.name.startsWith('data[')) {
                    const fieldName = item.name.substring(5, item.name.length - 1);
                    data[fieldName] = item.value;
                }
            });

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    data: data,
                    action: 'save'
                },
                success: function(response) {
                    if (response.success) {
                        showSaveMessage(response.message, 'success');
                    } else {
                        showSaveMessage('Error saving draft.', 'danger');
                    }
                    setTimeout(hideSaveMessage, 3000);
                },
                error: function(xhr) {
                    showSaveMessage('Error saving draft. Please check your connection.', 'danger');
                    setTimeout(hideSaveMessage, 3000);
                    console.error('Auto-save error:', xhr.responseText);
                }
            });
        }

        // Auto-save every 10 seconds
        saveTimer = setInterval(saveFormData, 10000);

        // Save on field change
        form.on('change', 'input, textarea, select', function() {
            clearInterval(saveTimer);
            saveFormData();
            saveTimer = setInterval(saveFormData, 10000);
        });
    });
</script>
@endpush
