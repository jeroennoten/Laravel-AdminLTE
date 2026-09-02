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

{{-- Support to auto select the old submitted values --}}

@if($errors->any() && $enableOldSupport)
@push('js')
<script>

    window._AdminLTE_Ready(function () {

        const el = document.getElementById(@json($id));

        if (! el) {
            return;
        }

        const oldOptions = @json(array_values((array) $getOldValue($errorKey, [])));

        Array.from(el.options).forEach(function (opt) {
            const value = opt.value || opt.text;
            opt.selected = oldOptions.map(String).includes(String(value));
        });
    });

</script>
@endpush
@endif
