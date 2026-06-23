@props([
    'name' => '',
    'label' => null,
    'type' => 'text',
    'placeholder' => null,
    'value' => null,
    'error' => null,
    'helper' => null,
    'required' => false,
    'disabled' => false,
    'icon' => null,
    'id' => null,
])

@php
$inputId = $id ?? $name;
$hasError = $errors && $errors->has($name);
$errorMessage = $hasError ? $errors->first($name) : $error;
@endphp

<div {{ $attributes->except(['class'])->merge(['class' => 'rf-input-group w-full']) }}>
    @if($label)
        <label for="{{ $inputId }}" class="rf-input-label block text-sm font-bold text-text-primary mb-1.5">
            {{ $label }}
            @if($required) <span class="text-error" aria-hidden="true">*</span> @endif
        </label>
    @endif

    <div class="rf-input-wrapper relative">
        @if($icon)
            <div class="rf-input-icon absolute inset-y-0 end-0 flex items-center pe-4 pointer-events-none text-text-muted" aria-hidden="true">
                {!! $icon !!}
            </div>
        @endif

        <input
            id="{{ $inputId }}"
            type="{{ $type }}"
            name="{{ $name }}"
            value="{{ $value }}"
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($errorMessage) aria-invalid="true" aria-describedby="{{ $inputId }}-error" @endif
            {{
                $attributes->merge(['class' => 'input-field rf-input '
                    . ($icon ? '!ps-4 !pe-12 ' : '')
                    . ($errorMessage ? '!border-error !shadow-none !ring-2 !ring-error/10 ' : ''),
                ])->only(['class'])
            }}
        />
    </div>

    @if($errorMessage)
        <p id="{{ $inputId }}-error" class="rf-input-error mt-1.5 text-xs font-semibold text-error" role="alert">
            {{ $errorMessage }}
        </p>
    @elseif($helper)
        <p class="rf-input-helper mt-1.5 text-xs text-text-muted">{{ $helper }}</p>
    @endif
</div>
