@extends('layouts.dashboard')

@section('title', 'Submissions for ' . $department)

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h2 class="h5 mb-0">Submissions for {{ $department }}</h2>
    </div>
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Checklist</th>
                    <th>Submitted On</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($submissions as $submission)
                    <tr>
                        <td>{{ $submission->checklist->title }}</td>
                        <td>{{ $submission->created_at->format('F d, Y') }}</td>
                        <td class="text-center">
                            <a href="{{ route('checklists.results.show', $submission->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye me-1"></i> View Results
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
