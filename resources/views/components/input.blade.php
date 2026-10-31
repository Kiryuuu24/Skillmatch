@props([
    'label' => null,
    'name' => null,
    'type' => 'text',
    'placeholder' => '',
    'required' => false,
    'class' => null,
    'error' => false,
])

@php
    $id = $name ?? Str::slug($label ?? $name);
    $inputClasses = trim(implode(' ', [
        'w-full px-4 py-3 rounded-xl text-base font-normal bg-white border transition-all duration-[--duration-fast] ease-[--ease-ease]',
        'focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/30 focus:border-[#FF6B35]',
        $error ? 'border-[#EF4444] focus:ring-[#EF4444]/30' : 'border-[#D1D5DB] hover:border-[#9CA3AF]',
        $class,
    ]));
@endphp

<div class="mb-4">
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-[#1A1A2E] mb-1.5">
            {{ $label }} @if($required)<span class="text-[#EF4444]">*</span>@endif
        </label>
    @endif

    <input
        type="{{ $type }}"
        id="{{ $id }}"
        name="{{ $name ?? $id }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        class="{{ $inputClasses }}"
        {{ $attributes }}
    />
</div>
