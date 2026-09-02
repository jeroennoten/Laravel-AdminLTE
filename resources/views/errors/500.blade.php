@extends('adminlte::errors.error-page', [
    'errorCode' => '500',
    'errorTheme' => 'danger',
    'errorTitle' => __('adminlte::adminlte.error_server_error_title'),
    'errorMessage' => __('adminlte::adminlte.error_server_error_message'),
])
