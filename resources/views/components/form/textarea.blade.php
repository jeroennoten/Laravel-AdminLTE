@extends('adminlte::components.form.input-group-component')

{{-- Set errors bag internallly --}}

@php($setErrorsBag($errors ?? null))

{{-- Set input group item section --}}

@section('input_group_item')

    {{-- Textarea --}}
    <textarea id="{{ $id }}" name="{{ $name }}"
        {{ $attributes->merge(['class' => $makeItemClass()]) }}
    >{{ $getOldValue($errorKey, $slot) }}</textarea>

@overwrite
