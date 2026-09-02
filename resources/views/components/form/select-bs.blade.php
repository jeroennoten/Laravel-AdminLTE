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

@push('js')
<script>

    window._AdminLTE_Ready(function () {

        const el = document.getElementById(@json($id));

        if (! el) {
            return;
        }

        {{-- Add support to auto select old submitted values in case of
             validation errors. --}}

        @if($errors->any() && $enableOldSupport)

            const oldOptions = @json(array_values((array) $getOldValue($errorKey, [])))
                .map(String);

            Array.from(el.options).forEach(function (opt) {
                opt.selected = oldOptions.includes(String(opt.value || opt.text));
            });

        @endif

        {{-- Initialize the Tom Select plugin. When the plugin is not loaded,
             the element stays a native Bootstrap 5 'form-select'. --}}

        if (typeof window.TomSelect === 'undefined') {
            return;
        }

        const usrCfg = Object.assign({}, @json((object) $config));

        if (el.multiple && typeof usrCfg.plugins === 'undefined') {
            usrCfg.plugins = ['remove_button'];
        }

        el.tomselect = new window.TomSelect(el, usrCfg);
    });

</script>
@endpush
