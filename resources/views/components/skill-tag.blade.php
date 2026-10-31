@props([
    'skill' => null,
    'variant' => 'default',  // default, primary, secondary, outline
    'size' => 'md',          // sm, md
    'class' => null,
])

@php
    $baseClasses = 'inline-flex items-center font-medium rounded-full transition-all duration-[--duration-fast] ease-[--ease-ease]';

    $variantClasses = match($variant) {
        'primary' => 'bg-[#FF6B35]/10 text-[#FF6B35] border border-[#FF6B35]/20',
        'secondary' => 'bg-[#F1F5F9] text-[#6B7280] border border-[#E5E7EB]',
        'outline' => 'bg-transparent text-[#1A1A2E] border border-[#D1D5DB] hover:bg-[#F4F6F9]',
        'default' => 'bg-[#F1F5F9] text-[#6B7280] border border-[#E5E7EB]',
    };

    $sizeClasses = match($size) {
        'sm' => 'px-2.5 py-0.5 text-xs',
        'md' => 'px-3 py-1 text-sm',
        'lg' => 'px-4 py-1.5 text-base',
        default => 'px-3 py-1 text-sm',
    };

    $classes = trim(implode(' ', [$baseClasses, $variantClasses, $sizeClasses, $class]));
@endphp

<span class="{{ $classes }}" {{ $attributes }}>
    {{ $skill ?? __('skill.unknown') }}
</span>