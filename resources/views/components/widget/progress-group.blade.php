<div {{ $attributes->merge(['class' => 'progress-group']) }}>

    {{-- Group label --}}
    @isset($label){{ $label }}@endisset

    {{-- Group value --}}
    <span class="float-end">
        @if($slot->isEmpty())<b>{{ $value }}</b>/{{ $max }}@else{{ $slot }}@endif
    </span>

    {{-- Group progress bar --}}
    @if(isset($attributes['id']))
        <x-adminlte-progress :value="$makePercentage()" theme="{{ $makeBarTheme() }}"
            size="{{ $size }}" class="mb-0" aria-label="{{ $makeBarLabel() }}"
            id="progress-{{ $attributes['id'] }}"/>
    @else
        <x-adminlte-progress :value="$makePercentage()" theme="{{ $makeBarTheme() }}"
            size="{{ $size }}" class="mb-0" aria-label="{{ $makeBarLabel() }}"/>
    @endif

</div>
