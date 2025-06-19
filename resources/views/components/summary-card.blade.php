@props(['count', 'label', 'icon', 'color', 'route'])

@php
    // This logic is now self-contained in the component!
    if (!function_exists('lighten')) {
        function lighten($hex, $percent) {
            if (empty($hex)) return '';
            $hex = ltrim($hex, '#');
            if (strlen($hex) == 3) $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
            $rgb = array_map('hexdec', str_split($hex, 2));
            foreach ($rgb as &$c) { $c = max(0, min(255, $c + (255 * ($percent/100)))); }
            return '#' . implode('', array_map(function($c) { return str_pad(dechex($c), 2, '0', STR_PAD_LEFT); }, $rgb));
        }
    }
    $gradient = "linear-gradient(135deg, {$color} 0%, " . lighten($color, 15) . " 100%)";
@endphp

<div class="card-hover p-6 rounded-xl text-white shadow-md" style="background-image: {{ $gradient }};">
    <div class="flex justify-between items-start">
        <div class="flex flex-col">
            <span class="text-4xl font-bold">{{ $count }}</span>
            <span class="mt-1 text-lg font-medium opacity-90">{{ $label }}</span>
        </div>
        <div class="text-4xl opacity-50">
            <i class="fas fa-{{ $icon }}"></i>
        </div>
    </div>
    <div class="mt-6 text-right">
        <a href="{{ $route }}" class="font-semibold opacity-80 hover:opacity-100 transition-opacity">
            View Details &rarr;
        </a>
    </div>
</div>