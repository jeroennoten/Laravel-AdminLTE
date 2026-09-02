@extends('adminlte::components.form.input-group-component')

{{-- Set errors bag internallly --}}

@php($setErrorsBag($errors ?? null))

{{-- Set input group item section --}}

@section('input_group_item')

    {{-- Input Color. Bootstrap 5 provides a native color control, so the
         legacy 'Bootstrap Colorpicker' jQuery plugin is not required. --}}
    <input id="{{ $id }}" name="{{ $name }}"
        value="{{ $makeColorValue($attributes->get('value')) }}"
        {{ $attributes->except('value')->merge($makeItemAttributes([
            'type' => 'color',
        ])) }}>

@overwrite

@once
@push('css')
<style>
    /* The input group rules of Bootstrap ('flex: 1 1 auto; width: 1%') beat
       the 3rem swatch width of '.form-control-color', so it is restored here.
       Otherwise the color swatch stretches over the whole row. */
    .input-group > .form-control-color {
        flex: 0 0 auto;
        width: 3rem;
    }
</style>
@endpush
@endonce

{{-- Keep the color of the addon icons in sync with the selected color --}}

@push('js')
<script>

    window._AdminLTE_Ready(function () {

        const el = document.getElementById(@json($id));

        if (! el) {
            return;
        }

        const group = el.closest('.input-group');

        const setAddonColor = function () {
            if (! group) {
                return;
            }

            group.querySelectorAll('.input-group-text > i').forEach(function (icon) {
                icon.style.color = el.value;
            });
        };

        el.addEventListener('input', setAddonColor);
        el.addEventListener('change', setAddonColor);

        setAddonColor();
    });

</script>
@endpush
