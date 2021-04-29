@extends('adminlte::components.form.input-group-component')

@section('input_group_item')

    {{-- Input --}}
    <input id="{{ $id }}" name="{{ $name }}"
        {{ $attributes->merge(['class' => $makeItemClass($errors->first($errorKey))]) }}>

@overwrite
