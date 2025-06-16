@extends('layouts.dashboard')
@section('title', 'Add New ' . $auditType . ' Audit')

@section('content')
<div class="card">
    <div class="card-header">Add New {{ $auditType }} Audit</div>
    <div class="card-body">
        <form action="{{ route('audits.store') }}" method="POST" enctype="multipart/form-data">
            @include('audits._form')
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Save Audit</button>
            </div>
        </form>
    </div>
</div>
@endsection