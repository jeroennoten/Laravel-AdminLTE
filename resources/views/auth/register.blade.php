@extends('adminlte::auth.auth-page', ['authType' => 'register'])

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@php
    $loginUrl = View::getSection('login_url') ?? config('adminlte.login_url', 'login');
    $registerUrl = View::getSection('register_url') ?? config('adminlte.register_url', 'register');

    $loginUrl = $layoutHelper->makeUrl($loginUrl);
    $registerUrl = $layoutHelper->makeUrl($registerUrl);
@endphp

@section('auth_header', __('adminlte::adminlte.register_message'))

@section('auth_body')
    <form action="{{ $registerUrl }}" method="post">
        @csrf

        {{-- Name field --}}
        <label for="name" class="visually-hidden">{{ __('adminlte::adminlte.full_name') }}</label>

        <div class="input-group mb-3">
            <input type="text" name="name" id="name"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}" placeholder="{{ __('adminlte::adminlte.full_name') }}" autofocus>

            <div class="input-group-text">
                <span class="bi bi-person-fill {{ config('adminlte.classes_auth_icon', '') }}"></span>
            </div>

            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Email field --}}
        <label for="email" class="visually-hidden">{{ __('adminlte::adminlte.email') }}</label>

        <div class="input-group mb-3">
            <input type="email" name="email" id="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" placeholder="{{ __('adminlte::adminlte.email') }}">

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

        {{-- Confirm password field --}}
        <label for="password_confirmation" class="visually-hidden">
            {{ __('adminlte::adminlte.retype_password') }}
        </label>

        <div class="input-group mb-3">
            <input type="password" name="password_confirmation" id="password_confirmation"
                class="form-control @error('password_confirmation') is-invalid @enderror"
                placeholder="{{ __('adminlte::adminlte.retype_password') }}">

            <div class="input-group-text">
                <span class="bi bi-lock-fill {{ config('adminlte.classes_auth_icon', '') }}"></span>
            </div>

            @error('password_confirmation')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Register button --}}
        <div class="d-grid">
            <button type="submit" class="btn {{ config('adminlte.classes_auth_btn', 'btn-primary') }}">
                <i class="bi bi-person-plus me-1"></i>
                {{ __('adminlte::adminlte.register') }}
            </button>
        </div>
    </form>
@include('adminlte::auth.social-links', ['fallbackText' => __('adminlte::adminlte.register')])
@stop

@section('auth_footer')
    <p class="my-0">
        <a href="{{ $loginUrl }}">
            {{ __('adminlte::adminlte.i_already_have_a_membership') }}
        </a>
    </p>
@stop
