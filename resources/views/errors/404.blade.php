@extends('adminlte::errors.error-page', [
    'errorCode' => '404',
    'errorTheme' => 'primary',
    'errorTitle' => __('adminlte::adminlte.error_not_found_title'),
    'errorMessage' => __('adminlte::adminlte.error_not_found_message'),
])
