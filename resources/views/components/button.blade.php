<button type="{{ $type }}" {{ $attributes->merge(['class' => "btn btn-{$theme}"]) }}>
    @isset($icon) <i class="{{ $icon }}"></i> @endisset
    {{ $label }}
</button>