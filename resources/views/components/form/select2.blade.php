@extends('adminlte::components.form.input-group-component')

{{-- Set errors bag internallly --}}

@php($setErrorsBag($errors ?? null))

{{-- Set input group item section --}}

@section('input_group_item')

    {{-- Select (Bootstrap 5 uses the 'form-select' class) --}}
    <select id="{{ $id }}" name="{{ $name }}"
        {{ $attributes->merge($makeItemAttributes()) }}>
        {{ $slot }}
    </select>

@overwrite

{{-- Add plugin initialization and configuration code --}}
{{-- NOTE: the Select2 plugin still requires jQuery --}}

@push('js')
<script>

    if (window.jQuery) {

        window.jQuery(function ($) {

            if (typeof $.fn.select2 === 'undefined') {
                return;
            }

            $('#{{ $id }}').select2( @json((object) $config) );

            // Add support to auto select old submitted values in case of
            // validation errors.

            @if($errors->any() && $enableOldSupport)

                let oldOptions = @json(collect($getOldValue($errorKey)))
                    .map(String);

                $('#{{ $id }} option').each(function () {
                    let value = $(this).val() || $(this).text();
                    $(this).prop('selected', oldOptions.includes(String(value)));
                });

                $('#{{ $id }}').trigger('change');

            @endif
        });
    }

</script>
@endpush

{{-- CSS workarounds for the Select2 plugin --}}
{{-- NOTE: this may change with newer plugin versions --}}

@once
@push('css')
<style type="text/css">

    {{-- Make the plugin container behave as a Bootstrap 5 input group item --}}
    .input-group > .select2-container {
        flex: 1 1 auto;
        width: 1% !important;
    }

    {{-- SM size setup --}}
    .input-group-sm .select2-selection--single {
        height: calc(1.8125rem + 2px) !important
    }
    .input-group-sm .select2-selection--single .select2-selection__rendered,
    .input-group-sm .select2-selection--single .select2-selection__placeholder {
        font-size: .875rem !important;
        line-height: 2.125;
    }
    .input-group-sm .select2-selection--multiple {
        min-height: calc(1.8125rem + 2px) !important
    }
    .input-group-sm .select2-selection--multiple .select2-selection__rendered {
        font-size: .875rem !important;
        line-height: normal;
    }

    {{-- LG size setup --}}
    .input-group-lg .select2-selection--single {
        height: calc(2.875rem + 2px) !important;
    }
    .input-group-lg .select2-selection--single .select2-selection__rendered,
    .input-group-lg .select2-selection--single .select2-selection__placeholder {
        font-size: 1.25rem !important;
        line-height: 2.25;
    }
    .input-group-lg .select2-selection--multiple {
        min-height: calc(2.875rem + 2px) !important
    }
    .input-group-lg .select2-selection--multiple .select2-selection__rendered {
        font-size: 1.25rem !important;
        line-height: 1.7;
    }

    {{-- Enhance the plugin to support the readonly attribute --}}
    select[readonly].select2-hidden-accessible + .select2-container {
        pointer-events: none;
        touch-action: none;
    }

    select[readonly].select2-hidden-accessible + .select2-container .select2-selection {
        background: var(--bs-secondary-bg, #e9ecef);
        box-shadow: none;
    }

    select[readonly].select2-hidden-accessible + .select2-container .select2-search__field {
        display: none;
    }

</style>
@endpush
@endonce
