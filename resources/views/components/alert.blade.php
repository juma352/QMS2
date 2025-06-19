@props(['type' => 'info', 'message'])

@php
    $typeClasses = [
        'success' => 'alert-success',
        'info' => 'alert-info',
        'warning' => 'alert-warning',
        'danger' => 'alert-danger',
    ];
    $alertClass = $typeClasses[$type] ?? 'alert-secondary';
@endphp

<div {{ $attributes->merge(['class' => 'alert ' . $alertClass . ' alert-dismissible fade show']) }} role="alert">
    {{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>