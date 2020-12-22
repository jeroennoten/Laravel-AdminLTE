@extends('adminlte::components.input-group-component')

@section('input_group_item')

    {{-- Input --}}
    <input id="{{ $name }}" name="{{ $name }}"
        {{ $attributes->merge(['class' => $makeItemClass($errors->first($name))]) }}>

@overwrite
