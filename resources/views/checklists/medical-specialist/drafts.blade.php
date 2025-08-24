@extends('layouts.dashboard')

@section('title', 'My Draft Audits')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">My Draft Audits</h3>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Program</th>
                                <th>Last Saved</th>
                                <th style="width: 200px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($drafts as $draft)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $draft->department_name ?: 'N/A' }}</td>
                                    <td>{{ $draft->progress->last_saved_at ? $draft->progress->last_saved_at->format('M d, Y H:i A') : 'Never' }}</td>
                                    <td>
                                        <a href="{{ route('checklists.medical-specialist.resume', ['submission' => $draft->id]) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-play"></i> Resume
                                        </a>
                                        <form action="{{ route('checklists.medical-specialist.drafts.destroy', ['submission' => $draft->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this draft?');" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">You have no draft audits.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
