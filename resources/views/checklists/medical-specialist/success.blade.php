
@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Checklist Submitted Successfully') }}</div>

                <div class="card-body">
                    <p>Your checklist has been submitted successfully.</p>
                    <p>Submission ID: {{ $submission }}</p>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
