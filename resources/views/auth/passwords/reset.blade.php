@extends('adminlte::auth.auth-page', ['authType' => 'login'])

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@php
    $passResetUrl = View::getSection('password_reset_url') ?? config('adminlte.password_reset_url', 'password/reset');
    $passResetUrl = $layoutHelper->makeUrl($passResetUrl);
@endphp

@section('auth_header', __('adminlte::adminlte.password_reset_message'))

@section('auth_body')
    <form action="{{ $passResetUrl }}" method="post">
        @csrf

        {{-- Token field --}}
        <input type="hidden" name="token" value="{{ $token }}">

        {{-- Email field --}}
        <label for="email" class="visually-hidden">{{ __('adminlte::adminlte.email') }}</label>

        <div class="input-group mb-3">
            <input type="email" name="email" id="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" placeholder="{{ __('adminlte::adminlte.email') }}" autofocus>

            <div class="input-group-text">
                <span class="bi bi-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
            </div>

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Password field --}}
        <label for="password" class="visually-hidden">{{ __('adminlte::adminlte.password') }}</label>

        <div class="input-group mb-3">
            <input type="password" name="password" id="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="{{ __('adminlte::adminlte.password') }}">

            <div class="input-group-text">
                <span class="bi bi-lock-fill {{ config('adminlte.classes_auth_icon', '') }}"></span>
            </div>

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Password confirmation field --}}
        <label for="password_confirmation" class="visually-hidden">
            {{ __('adminlte::adminlte.retype_password') }}
        </label>

        <div class="input-group mb-3">
            <input type="password" name="password_confirmation" id="password_confirmation"
                class="form-control @error('password_confirmation') is-invalid @enderror"
                placeholder="{{ trans('adminlte::adminlte.retype_password') }}">

            <div class="input-group-text">
                <span class="bi bi-lock-fill {{ config('adminlte.classes_auth_icon', '') }}"></span>
            </div>

            @error('password_confirmation')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Confirm password reset button --}}
        <div class="d-grid">
            <button type="submit" class="btn {{ config('adminlte.classes_auth_btn', 'btn-primary') }}">
                <i class="bi bi-arrow-repeat me-1"></i>
                {{ __('adminlte::adminlte.reset_password') }}
            </button>
        </div>
    </form>
@stop
