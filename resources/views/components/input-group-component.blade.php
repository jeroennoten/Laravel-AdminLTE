<div class="{{ $makeFormGroupClass() }}">

    {{-- Input label --}}
    @isset($label)
        <label for="{{ $name }}" @isset($labelClass) class="{{ $labelClass }}" @endisset>
            {{ $label }}
        </label>
    @endisset

    {{-- Input group --}}
    <div class="{{ $makeInputGroupClass() }}">

        {{-- Input prepend slot --}}
        @isset($prependSlot)
            <div class="input-group-prepend">{{ $prependSlot }}</div>
        @endisset

        {{-- Input group item --}}
        @yield('input_group_item')

        {{-- Input append slot --}}
        @isset($appendSlot)
            <div class="input-group-append">{{ $appendSlot }}</div>
        @endisset

    </div>

    {{-- Error feedback --}}
    @if(! isset($disableFeedback))
        @error($name)
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    @endif

</div>
