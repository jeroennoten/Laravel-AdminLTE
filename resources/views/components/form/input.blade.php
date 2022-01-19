@extends('adminlte::components.form.input-group-component')

@section('input_group_item')

    {{-- Input --}}
    <input id="{{ $id }}" name="{{ $name }}"
        value="{{ $getOldValue($errorKey, $attributes->get('value')) }}"
        {{ $attributes->merge(['class' => $makeItemClass()]) }}>

@overwrite
