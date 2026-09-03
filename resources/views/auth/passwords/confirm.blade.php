@extends('adminlte::master')

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@section('adminlte_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body', 'lockscreen bg-body-secondary')

@php
    $passResetUrl = View::getSection('password_reset_url') ?? config('adminlte.password_reset_url', 'password/reset');
    $dashboardUrl = View::getSection('dashboard_url') ?? config('adminlte.dashboard_url', 'home');

    $passResetUrl = $layoutHelper->makeUrl($passResetUrl);
    $dashboardUrl = $layoutHelper->makeUrl($dashboardUrl);
@endphp

@section('body')
    <main class="lockscreen-wrapper">

        {{-- Lockscreen logo --}}
        <h1 class="lockscreen-logo">
            <a href="{{ $dashboardUrl }}">
                <img src="{{ asset(config('adminlte.logo_img')) }}"
                     alt="{{ config('adminlte.logo_img_alt') }}" height="50">
                {!! config('adminlte.logo', '<b>Admin</b>LTE') !!}
            </a>
        </h1>

        {{-- Lockscreen user name --}}
        <div class="lockscreen-name">
            {{ isset(Auth::user()->name) ? Auth::user()->name : Auth::user()->email }}
        </div>

        {{-- Lockscreen item --}}
        <div class="lockscreen-item">
            @if(config('adminlte.usermenu_image'))
                <div class="lockscreen-image">
                    <img src="{{ Auth::user()->adminlte_image() }}" alt="{{ Auth::user()->name }}">
                </div>
            @endif

            <form method="POST" action="{{ route('password.confirm') }}"
                class="lockscreen-credentials @if(! config('adminlte.usermenu_image')) ms-0 @endif">
                @csrf

                <label for="password" class="visually-hidden">
                    {{ __('adminlte::adminlte.password') }}
                </label>

                <div class="input-group">
                    <input id="password" type="password" name="password"
                        class="form-control shadow-none @error('password') is-invalid @enderror"
                        placeholder="{{ __('adminlte::adminlte.password') }}" required autofocus>

                    <div class="input-group-text border-0 bg-transparent px-1">
                        <button type="submit" class="btn shadow-none">
                            <i class="bi bi-box-arrow-right text-body-secondary"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Password error alert --}}
        @error('password')
            <div class="text-center mb-3" role="alert">
                <b class="text-danger">{{ $message }}</b>
            </div>
        @enderror

        {{-- Help block --}}
        <div class="text-center">
            {{ __('adminlte::adminlte.confirm_password_message') }}
        </div>

        {{-- Additional links --}}
        <div class="text-center">
            <a href="{{ $passResetUrl }}">
                {{ __('adminlte::adminlte.i_forgot_my_password') }}
            </a>
        </div>

    </main>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
@stop
