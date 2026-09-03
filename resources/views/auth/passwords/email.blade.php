@extends('adminlte::auth.auth-page', ['authType' => 'login'])

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@php
    $passEmailUrl = View::getSection('password_email_url') ?? config('adminlte.password_email_url', 'password/email');
    $loginUrl = View::getSection('login_url') ?? config('adminlte.login_url', 'login');
    $registerUrl = View::getSection('register_url') ?? config('adminlte.register_url', 'register');

    $passEmailUrl = $layoutHelper->makeUrl($passEmailUrl);
    $loginUrl = $layoutHelper->makeUrl($loginUrl);
    $registerUrl = $layoutHelper->makeUrl($registerUrl);
@endphp

@section('auth_header', __('adminlte::adminlte.password_reset_message'))

@section('auth_body')

    @if(session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ $passEmailUrl }}" method="post">
        @csrf

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

        {{-- Send reset link button --}}
        <div class="d-grid">
            <button type="submit" class="btn {{ config('adminlte.classes_auth_btn', 'btn-primary') }}">
                <i class="bi bi-send me-1"></i>
                {{ __('adminlte::adminlte.send_password_reset_link') }}
            </button>
        </div>
    </form>

@stop

@section('auth_footer')
    {{-- Login link. Without it the page is a dead end, the reference layout
         provides the same way back. --}}
    @if($loginUrl)
        <p class="my-0">
            <a href="{{ $loginUrl }}">
                {{ __('adminlte::adminlte.i_already_have_a_membership') }}
            </a>
        </p>
    @endif

    {{-- Register link --}}
    @if($registerUrl)
        <p class="my-0">
            <a href="{{ $registerUrl }}">
                {{ __('adminlte::adminlte.register_a_new_membership') }}
            </a>
        </p>
    @endif
@stop
