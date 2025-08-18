@extends('layouts.dashboard')

@section('title', 'Medical Specialist Checklist Drafts')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Medical Specialist Checklist Drafts</h4>
                </div>
                <div class="card-body">
                    @if($drafts->isEmpty())
                        <p>No drafts found.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Submission ID</th>
                                        <th>Checklist Title</th>
                                        <th>Status</th>
                                        <th>Last Saved</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($drafts as $draft)
                                    <tr>
                                        <td>{{ $draft->id }}</td>
                                        <td>{{ $draft->checklist->title ?? 'N/A' }}</td>
                                        <td>{{ ucfirst($draft->status) }}</td>
                                        <td>{{ $draft->progress->last_saved_at ? $draft->progress->last_saved_at->setTimezone('Africa/Nairobi')->format('M d, Y H:i') : 'N/A' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('checklists.medical-specialist.resume', $draft->id) }}" class="btn btn-sm btn-primary">Resume</a>
                                                <form action="{{ route('checklists.medical-specialist.drafts.destroy', $draft->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this draft?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection