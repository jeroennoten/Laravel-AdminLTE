{{--
    The page rendered by the integration check. It exercises the layout, the
    view composer that binds the AdminLte instance, the asset resolution and a
    few of the blade components, all at once.
--}}
@extends('adminlte::page')

@section('title', 'Integration check')

@section('content_header')
    <x-adminlte-content-header title="Integration check"
        :breadcrumbs="[['label' => 'Home', 'url' => '/'], ['label' => 'Check']]"/>
@stop

@section('content')
    <x-adminlte-card title="Integration check" theme="primary">
        <x-adminlte-input name="smoke" label="Smoke"/>
    </x-adminlte-card>
@stop
