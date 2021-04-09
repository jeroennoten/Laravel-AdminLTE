@extends('adminlte::components.input-group-component')

@section('input_group_item')

    {{-- Textarea --}}
    <textarea id="{{ $name }}" name="{{ $name }}"
        {{ $attributes->merge(['class' => $makeItemClass($errors->first($name))]) }}
    >{{ $slot }}</textarea>

@overwrite
