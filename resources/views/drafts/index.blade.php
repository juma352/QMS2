@extends('layouts.dashboard')

@section('title', 'Drafts')

@section('content')
<div class="container">
    <h1>Drafts</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Checklist</th>
                <th>User</th>
                <th>Last Updated</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($drafts as $draft)
                <tr>
                    <td>{{ $draft->checklist->title }}</td>
                    <td>{{ $draft->user->name }}</td>
                    <td>{{ $draft->updated_at->format('Y-m-d H:i:s') }}</td>
                    <td>
                        <a href="{{ route('checklists.show', $draft->checklist_id) }}?draft_id={{ $draft->id }}" class="btn btn-primary">Continue</a>
                        <form action="{{ route('drafts.destroy', $draft->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
