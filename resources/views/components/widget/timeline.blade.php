<div {{ $attributes->merge(['class' => $makeTimelineClass()]) }}>

    {{-- Timeline entries --}}
    {{ $slot }}

    {{-- Closing icon --}}
    @if(! empty($endIcon))
        <div>
            <i class="{{ $makeEndIconClass() }}" aria-hidden="true"></i>
        </div>
    @endif

</div>
