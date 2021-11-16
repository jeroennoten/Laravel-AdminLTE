@extends('adminlte::components.form.input-group-component')

@section('input_group_item')

    {{-- Select --}}
    <select id="{{ $id }}" name="{{ $name }}"
        {{ $attributes->merge(['class' => $makeItemClass()]) }}>
        {{ $slot }}
    </select>

@overwrite

{{-- Support to auto select old submitted values --}}

@if($errors->any())
@push('js')
<script>

    $(() => {

        let oldOptions = @json(collect($makeItemValue($errorKey)));

        $('#{{ $id }} option').each(function()
        {
            let value = $(this).val() || $(this).text();

            if (oldOptions.includes(value))
            {
                $(this).prop('selected', true);
            }
        });
    });

</script>
@endpush
@endif
