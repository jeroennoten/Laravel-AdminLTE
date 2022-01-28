@extends('adminlte::components.form.input-group-component')

@section('input_group_item')

    {{-- Select --}}
    <select id="{{ $id }}" name="{{ $name }}"
        {{ $attributes->merge(['class' => $makeItemClass()]) }}>
        {{ $slot }}
    </select>

@overwrite

{{-- Add plugin initialization and configuration code --}}

@push('js')
<script>

    $(() => {
        $('#{{ $id }}').selectpicker( @json($config) );

        // Add support to auto select old submitted values in case of
        // validation errors.

        @if($errors->any() && $enableOldSupport)
            let oldOptions = @json(collect($getOldValue($errorKey)));
            $('#{{ $id }}').selectpicker('val', oldOptions);
        @endif
    })

</script>
@endpush
