@props(['activity'])

<div class="flex items-start animate-fade-in" style="animation-delay: {{ $loop->index * 0.1 }}s;">
    <div class="w-8 h-8 rounded-full bg-[{{ $activity->color ?? 'var(--primary)' }}]/10 text-[{{ $activity->color ?? 'var(--primary)' }}] flex items-center justify-center mr-3 mt-1">
        <i class="fas fa-{{ $activity->icon ?? 'history' }} text-sm"></i>
    </div>
    <div>
        <p class="text-sm font-medium text-[var(--text-dark)]">{{ $activity->description }}</p>
        <p class="text-xs text-[var(--text-light)]">{{ $activity->created_at->diffForHumans() }}</p>
    </div>
</div>