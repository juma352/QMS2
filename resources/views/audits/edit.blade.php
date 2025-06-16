@extends('layouts.dashboard')
@section('title', 'Edit ' . $audit->audit_type . ' Audit')

@section('content')
<div class="card">
    <div class="card-header">Edit {{ $audit->audit_type }} Audit</div>
    <div class="card-body">
        <form action="{{ route('audits.update', $audit->id) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @include('audits._form')
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Update Audit</button>
            </div>
        </form>
    </div>
</div>
@endsection