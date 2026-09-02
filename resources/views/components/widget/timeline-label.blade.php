<div {{ $attributes->merge(['class' => 'time-label']) }}>
    {{-- Label badge --}}
    <span class="{{ $makeLabelClass() }}">@isset($label){{ $label }}@else{{ $slot }}@endisset</span>
</div>
