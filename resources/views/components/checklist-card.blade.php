@props(['checklist', 'submission', 'index'])

@php
    $isCompleted = (bool) $submission;
    $statusClass = $isCompleted ? 'border-green-500' : 'border-blue-500';
    $animationDelay = ($index * 100) . 'ms';
@endphp

<div class="card-hover bg-white rounded-lg shadow-sm border-t-4 {{ $statusClass }} p-5 flex flex-col justify-between opacity-0 animate-fade-in" style="animation-delay: {{ $animationDelay }};">
    <div>
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-clipboard-check text-gray-400 mr-3"></i>
                {{ $checklist->title }}
            </h3>
            @if ($isCompleted)
                <span class="bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">Completed</span>
            @else
                <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full">Pending</span>
            @endif
        </div>
        <p class="text-gray-600 text-sm leading-relaxed mb-4">
            {{ $checklist->description ?? 'Evaluate compliance for this area.' }}
        </p>
    </div>
    <div class="flex gap-3 mt-auto">
        @if ($isCompleted)
            <a href="#" class="w-full text-center bg-gray-200 text-gray-500 font-medium py-2 px-4 rounded-md text-sm cursor-not-allowed">
                Completed
            </a>
            <a href="{{ route('checklists.results.show', $submission->id) }}" class="w-full text-center bg-green-600 text-white font-medium py-2 px-4 rounded-md text-sm hover:bg-green-700 transition-colors">
                View Results
            </a>
        @else
            <a href="{{ route('checklists.show', $checklist->slug) }}" class="w-full text-center bg-blue-600 text-white font-medium py-2 px-4 rounded-md text-sm hover:bg-blue-700 transition-colors">
                Fill Checklist
            </a>
        @endif
    </div>
</div>