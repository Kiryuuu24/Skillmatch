@props([
    'variant' => 'primary',  // primary, secondary, ghost, danger
    'size' => 'md',         // sm, md, lg
    'href' => null,
    'class' => null,
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium transition-all duration-[--duration-fast] ease-[--ease-ease] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FF6B35]/30 disabled:opacity-50 disabled:pointer-events-none';

    $variantClasses = match($variant) {
        'primary' => 'bg-[#FF6B35] text-white hover:bg-[#E55A2B] active:scale-[0.98]',
        'secondary' => 'bg-[#F1F5F9] text-[#1A1A2E] hover:bg-[#E2E8F0] border border-[#D1D5DB]',
        'ghost' => 'bg-transparent text-[#1A1A2E] hover:bg-[#F1F5F9] border border-transparent',
        'danger' => 'bg-[#EF4444] text-white hover:bg-[#DC2626]',
        'success' => 'bg-[#10B981] text-white hover:bg-[#059669]',
        'outline' => 'bg-transparent text-[#FF6B35] border border-[#FF6B35] hover:bg-[#FF6B35]/10',
        default => $baseClasses,
    };

    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2.5 text-base',
        'lg' => 'px-6 py-3 text-lg font-semibold',
        'icon' => 'w-10 h-10 p-0',
        default => 'px-4 py-2.5 text-base',
    };

    $classes = trim(implode(' ', [$baseClasses, $variantClasses, $sizeClasses, $class]));
@endphp

@if($href)
    <a href="{{ $href }}" class="{{ $classes }}" {{ $attributes }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type ?? 'button' }}" class="{{ $classes }}" {{ $attributes }}>
        {{ $slot }}
    </button>
@endif
