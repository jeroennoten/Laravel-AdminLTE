@extends('adminlte::errors.error-page', [
    'errorCode' => '401',
    'errorTheme' => 'warning',
    'errorTitle' => __('adminlte::adminlte.error_unauthorized_title'),
    'errorMessage' => __('adminlte::adminlte.error_unauthorized_message'),
])
