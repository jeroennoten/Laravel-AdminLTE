<div class="{{ $makeFormGroupClass() }}">

    {{-- Input label --}}
    @isset($label)
        <label for="{{ $id }}" @isset($labelClass) class="{{ $labelClass }}" @endisset>
            {{ $label }}
        </label>
    @endisset

    {{-- Input group --}}
    <div class="{{ $makeInputGroupClass($errors->first($errorKey)) }}">

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
        @error($errorKey)
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    @endif

</div>

{{-- Extra style customization for invalid input groups --}}

@once
@push('css')
<style type="text/css">

    {{-- Highlight invalid input groups with a box-shadow --}}

    .adminlte-invalid-igroup {
        box-shadow: 0 .25rem 0.5rem rgba(0,0,0,.1);
    }

    {{-- Setup a red border on elements inside prepend/append add-ons --}}

    .adminlte-invalid-igroup > .input-group-prepend > *,
    .adminlte-invalid-igroup > .input-group-append > * {
        border-color: #dc3545 !important;
    }

</style>
@endpush
@endonce
