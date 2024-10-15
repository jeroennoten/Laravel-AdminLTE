@extends('adminlte::components.form.input-group-component')

{{-- Set errors bag internallly --}}

@php($setErrorsBag($errors ?? null))

{{-- Set input group item section --}}

@section('input_group_item')

    <div class="custom-file">

        {{-- Custom file input --}}
        <input type="file" id="{{ $id }}" name="{{ $name }}"
            {{ $attributes->merge(['class' => $makeItemClass()]) }}>

        {{-- Custom file label --}}
        <label class="custom-file-label text-truncate" for="{{ $id }}"
            @isset($legend) data-browse="{{ $legend }}" @endisset>
            {{ $placeholder }}
        </label>

    </div>

@overwrite

{{-- Add the plugin initialization code --}}

@once
@push('js')
<script>

    $(() => {
        bsCustomFileInput.init();
    })

</script>
@endpush
@endonce

{{-- Setup the height and font size of the plugin when using sm/lg sizes --}}
{{-- NOTE: this may change with newer plugin or Bootstrap versions --}}

@once
@push('css')
<style type="text/css">

    {{-- SM size setup --}}
    .input-group-sm .custom-file-label:after {
        height: 1.8125rem;
        line-height: 1.25;
    }
    .input-group-sm .custom-file-label {
        height: calc(1.8125rem + 2px);
        line-height: 1.25;
    }
    .input-group-sm .custom-file {
        height: calc(1.8125rem + 2px);
        font-size: .875rem;
    }

    {{-- LG size setup --}}
    .input-group-lg .custom-file-label:after {
        height: 2.875rem;
        line-height: 1.6;
    }
    .input-group-lg .custom-file-label {
        height: calc(2.875rem + 2px);
        line-height: 1.6;
    }
    .input-group-lg .custom-file {
        height: calc(2.875rem + 2px);
        font-size: 1.25rem;
    }

</style>
@endpush
@endonce
