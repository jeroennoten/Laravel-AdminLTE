@extends('adminlte::components.form.input-group-component')

{{-- Set errors bag internallly --}}

@php($setErrorsBag($errors ?? null))

{{-- Set input group item section --}}

@section('input_group_item')

    {{-- Input Switch. Bootstrap 5.3 provides a native switch control, so the
         legacy 'Bootstrap Switch' jQuery plugin is not required anymore. --}}
    <div class="{{ $makeSwitchWrapperClass() }}">

        <input type="checkbox" role="switch" id="{{ $id }}" name="{{ $name }}"
            @if($isChecked()) checked @endif
            @if(! empty($config['disabled'])) disabled @endif
            @if(! empty($config['readonly'])) readonly @endif
            {{ $attributes->merge($makeItemAttributes(['value' => 'true'])) }}>

        @if($getSwitchLabel())
            <label class="form-check-label mb-0" for="{{ $id }}">
                {{ $getSwitchLabel() }}
            </label>
        @endif

    </div>

@overwrite

{{-- Setup the checked state color when requested through the config --}}

@if($getSwitchColor())
@push('css')
<style type="text/css">

    #{{ $id }}.form-check-input:checked {
        background-color: var(--bs-{{ $getSwitchColor() }});
        border-color: var(--bs-{{ $getSwitchColor() }});
    }

</style>
@endpush
@endif

{{-- Setup the switch sizes and the custom invalid style --}}

@once
@push('css')
<style type="text/css">

    {{-- LG size setup --}}
    .input-group-lg .form-switch .form-check-input {
        height: 1.5rem;
        width: 3rem;
    }

    {{-- SM size setup --}}
    .input-group-sm .form-switch .form-check-input {
        height: 1rem;
        width: 2rem;
    }

    {{-- Custom invalid style setup --}}

    .adminlte-invalid-iswgroup > .form-check,
    .adminlte-invalid-iswgroup > .input-group-text,
    .adminlte-invalid-iswgroup > .btn {
        box-shadow: 0 .25rem 0.5rem rgba(var(--bs-danger-rgb, 220, 53, 69), .25);
    }

</style>
@endpush
@endonce
