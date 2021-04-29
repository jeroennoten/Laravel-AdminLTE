@extends('adminlte::components.form.input-group-component')

@section('input_group_item')

    {{-- Select --}}
    <select id="{{ $id }}" name="{{ $name }}"
        {{ $attributes->merge(['class' => $makeItemClass($errors->first($errorKey))]) }}>
        {{ $slot }}
    </select>

@overwrite

{{-- Add plugin initialization and configuration code --}}

@push('js')
<script>

    $(() => {
        $('#{{ $id }}').selectpicker( @json($config) );
    })

</script>
@endpush
