@extends('adminlte::errors.error-page', [
    'errorCode' => '419',
    'errorTheme' => 'warning',
    'errorTitle' => __('adminlte::adminlte.error_page_expired_title'),
    'errorMessage' => __('adminlte::adminlte.error_page_expired_message'),
])
