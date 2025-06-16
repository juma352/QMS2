@extends('layouts.dashboard')

@section('title', $checklist->title)

@section('content')
<div class="card shadow-sm border-0 rounded-3">
    <div class="card-header bg-primary text-white p-3">
        <h2 class="h5 mb-0 fw-semibold">{{ $checklist->title }}</h2>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('checklists.store', $checklist->id) }}">
            @csrf
            @foreach($itemsBySection as $section => $items)
                <fieldset class="mb-5">
                    <legend class="h6 fw-bold border-bottom pb-2 mb-3"><i class="fas fa-bookmark me-2"></i>{{ $section }}</legend>
                    @foreach($items as $item)
                        <div class="mb-4">
                            <label for="item-{{$item->id}}" class="form-label">{{ $loop->parent->iteration }}.{{ $loop->iteration }}. {{ $item->question_text }}</label>
                            <select name="ratings[{{ $item->id }}]" id="item-{{$item->id}}" class="form-select" required>
                                <option value="">Select a rating</option>
                                <option value="1">1 (Poor)</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4 (Adequate)</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7 (Excellent)</option>
                            </select>
                        </div>
                    @endforeach
                </fieldset>
            @endforeach

            <div class="border-top pt-3 text-end">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-2"></i>Submit {{ $checklist->title }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection