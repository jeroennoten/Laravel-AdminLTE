@extends('adminlte::errors.error-page', [
    'errorCode' => '429',
    'errorTheme' => 'warning',
    'errorTitle' => __('adminlte::adminlte.error_too_many_requests_title'),
    'errorMessage' => __('adminlte::adminlte.error_too_many_requests_message'),
])
