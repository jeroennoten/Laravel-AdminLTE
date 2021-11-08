@extends('adminlte::components.form.input-group-component')

@section('input_group_item')

    {{-- Textarea --}}
    <textarea id="{{ $id }}" name="{{ $name }}"
        {{ $attributes->merge(['class' => $makeItemClass()]) }}
    >{{ $makeItemValue($errorKey, $slot) }}</textarea>

@overwrite
