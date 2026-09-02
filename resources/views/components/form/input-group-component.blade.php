{{-- Setup the input group component structure --}}

<div class="{{ $makeFormGroupClass() }}">

    {{-- Input label --}}
    @isset($label)
        <label for="{{ $id }}" class="{{ $makeLabelClass() }}">
            {{ $label }}
        </label>
    @endisset

    {{-- Input group --}}
    <div class="{{ $makeInputGroupClass() }}">

        {{-- Input prepend slot. Note that Bootstrap 5 dropped the
             'input-group-prepend' wrapper, the addons are now direct
             children of the 'input-group' element. --}}
        @isset($prependSlot)
            {{ $prependSlot }}
        @endisset

        {{-- Input group item --}}
        @yield('input_group_item')

        {{-- Input append slot. Note that Bootstrap 5 dropped the
             'input-group-append' wrapper, the addons are now direct
             children of the 'input-group' element. --}}
        @isset($appendSlot)
            {{ $appendSlot }}
        @endisset

    </div>

    {{-- Error feedback --}}
    @if($isInvalid())
        <span id="{{ $makeInvalidFeedbackId() }}"
              class="{{ $makeInvalidFeedbackClass() }}" role="alert">
            <strong>{{ $errors->first($errorKey) }}</strong>
        </span>
    @endif

    {{-- Bottom slot --}}
    @isset($bottomSlot)
        {{ $bottomSlot }}
    @endisset

</div>

{{-- Extra style customization for invalid input groups --}}

@once
@push('css')
<style type="text/css">

    .adminlte-invalid-igroup {
        box-shadow: 0 .25rem 0.5rem rgba(0, 0, 0, .1);
    }

    .adminlte-invalid-igroup > .input-group-text,
    .adminlte-invalid-igroup > .btn {
        border-color: var(--bs-danger, #dc3545) !important;
    }

</style>
@endpush
@endonce
