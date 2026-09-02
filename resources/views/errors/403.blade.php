@extends('adminlte::errors.error-page', [
    'errorCode' => '403',
    'errorTheme' => 'warning',
    'errorTitle' => __('adminlte::adminlte.error_forbidden_title'),
    'errorMessage' => __('adminlte::adminlte.error_forbidden_message'),
])
