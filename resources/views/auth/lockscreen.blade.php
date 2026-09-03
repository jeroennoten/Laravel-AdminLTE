@extends('adminlte::master')

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@section('adminlte_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body', 'lockscreen bg-body-secondary')

@php
    $lockedUser = $user ?? Auth::user();
    $lockedName = $lockedUser->name ?? ($lockedUser->email ?? '');

    $dashboardUrl = View::getSection('dashboard_url') ?? config('adminlte.dashboard_url', 'home');
    $logoutUrl = View::getSection('logout_url') ?? config('adminlte.logout_url', 'logout');

    $dashboardUrl = $layoutHelper->makeUrl($dashboardUrl);
    $logoutUrl = $layoutHelper->makeUrl($logoutUrl);

    $unlockUrl = $unlockUrl ?? (Route::has('adminlte.lockscreen.unlock')
        ? route('adminlte.lockscreen.unlock')
        : url('adminlte/lockscreen/unlock'));
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
        <div class="lockscreen-name" title="{{ __('adminlte::adminlte.full_name') }}">
            {{ $lockedName }}
        </div>

        {{-- Lockscreen item --}}
        <div class="lockscreen-item">
            @if(config('adminlte.usermenu_image'))
                <div class="lockscreen-image">
                    <img src="{{ $lockedUser->adminlte_image() }}" alt="{{ $lockedName }}">
                </div>
            @endif

            <form method="POST" action="{{ $unlockUrl }}"
                class="lockscreen-credentials @if(! config('adminlte.usermenu_image')) ms-0 @endif">
                @csrf

                <label for="password" class="visually-hidden">
                    {{ __('adminlte::adminlte.password') }}
                </label>

                <div class="input-group">
                    <input id="password" type="password" name="password"
                        class="form-control shadow-none @error('password') is-invalid @enderror"
                        placeholder="{{ __('adminlte::adminlte.password') }}"
                        autocomplete="current-password" required autofocus>

                    <div class="input-group-text border-0 bg-transparent px-1">
                        <button type="submit" class="btn shadow-none"
                                aria-label="{{ __('adminlte::adminlte.sign_in') }}">
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
            {{ __('adminlte::adminlte.lockscreen_message') }}
        </div>

        {{-- Lockscreen footer --}}
        <div class="lockscreen-footer text-center">
            @if($logoutUrl)
                <form method="POST" action="{{ $logoutUrl }}">
                    @csrf
                    @if(config('adminlte.logout_method'))
                        {{ method_field(config('adminlte.logout_method')) }}
                    @endif

                    <button type="submit" class="btn btn-link p-0">
                        <i class="bi bi-box-arrow-left me-1"></i>
                        {{ __('adminlte::adminlte.log_out') }}
                    </button>
                </form>
            @endif
        </div>

    </main>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
@stop
