@extends('adminlte::components.form.input-group-component')

{{-- Set errors bag internallly --}}

@php($setErrorsBag($errors ?? null))

{{-- Set input group item section --}}

@section('input_group_item')

    {{-- File input. Note Bootstrap 5 dropped the 'custom-file' structure and
         the 'form-control-file' class, a file input is now styled with the
         regular 'form-control' class. --}}
    <input type="file" id="{{ $id }}" name="{{ $name }}"
        {{ $attributes->merge($makeItemAttributes()) }}>

    {{-- Optional legend. The native browse button can't be relabeled on
         Bootstrap 5, so the legend is rendered as an input group addon. --}}
    @isset($legend)
        <label class="input-group-text" for="{{ $id }}">{{ $legend }}</label>
    @endisset

@overwrite
