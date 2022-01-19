@extends('adminlte::components.form.input-group-component')

@section('input_group_item')

    {{-- Textarea --}}
    <textarea id="{{ $id }}" name="{{ $name }}"
        {{ $attributes->merge(['class' => $makeItemClass()]) }}
    >{{ $getOldValue($errorKey, $slot) }}</textarea>

@overwrite
