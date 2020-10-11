@extends('adminlte::components.input-group-component')

@section('input_group_item')

    <div class="custom-file">

        {{-- Custom file input --}}
        <input type="file" id="{{ $name }}" name="{{ $name }}"
            {{ $attributes->merge(['class' => $makeItemClass($errors->first($name))]) }}>

        {{-- Custom file label --}}
        <label class="{{ $makeCustomFileLabelClass() }}" for="{{ $name }}"
            @isset($legend) data-browse="{{ $legend }}" @endisset>
            {{ $placeholder }}
        </label>

    </div>

@overwrite

{{-- Add the plugin initialization code --}}

@once
@push('js')
    <script>
        $(() => {bsCustomFileInput.init();})
    </script>
@endpush
@endonce

{{-- Fix the height of the custom-file-label for input-group-[sm,lg] --}}
{{-- NOTE: this may not be needed with newer Bootstrap versions (>= 4.5) --}}

@once
@push('css')
    <style type="text/css">
        .input-group-sm .custom-file-label:after {
            height: 1.8125rem;
        }
        .input-group-lg .custom-file-label:after {
            height: 2.875rem;
        }
        .input-group-sm .custom-file-label {
            height: calc(1.8125rem + 2px);
        }
        .input-group-lg .custom-file-label {
            height: calc(2.875rem + 2px);
        }
        .input-group-sm .custom-file {
            height: calc(1.8125rem + 2px);
        }
        .input-group-lg .custom-file {
            height: calc(2.875rem + 2px);
        }
    </style>
@endpush
@endonce
