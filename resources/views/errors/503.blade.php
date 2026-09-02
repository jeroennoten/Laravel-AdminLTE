@extends('adminlte::errors.error-page', [
    'errorCode' => '503',
    'errorTheme' => 'warning',
    'errorTitle' => __('adminlte::adminlte.error_service_unavailable_title'),
    'errorMessage' => __('adminlte::adminlte.error_service_unavailable_message'),
])

@section('error_icon')
    <i class="bi bi-tools text-warning" style="font-size: 4rem" aria-hidden="true"></i>
@stop
