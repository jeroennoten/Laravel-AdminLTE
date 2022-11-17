@extends('adminlte::components.form.input-group-component')

@section('input_group_item')

    {{-- Input --}}
    <input id="{{ $id }}" name="{{ $name }}"
        value="@if($isEscaped) {{ $getOldValue($errorKey, $attributes->get('value')) }} @else {!! $getOldValue($errorKey, $attributes->get('value')) !!} @endif"
        {{ $attributes->merge(['class' => $makeItemClass()]) }}>

@overwrite
