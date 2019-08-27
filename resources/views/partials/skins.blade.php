@if(Auth::check() and config('adminlte.skin_mode') == 'user')
    <li class="dropdown notifications-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="{{config('adminlte.skin_icon')}}"></i>
        </a>
        <ul class="dropdown-menu">
            <li class="header">{{ trans('adminlte::skins.choose_skin')}}</li>
            <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu" style="max-height: 250px !important;">
                    <li>
                        <a href="{{route('adminlteskinchange', 'blue')}}">
                            <i class="fas fa-square" style="color: #3c8dbc; margin-right: 10px; transform: scale(1.5,1)"></i> {{ trans('adminlte::skins.blue')}} @if(Auth::user()->skin == 'blue') <i class="fas fa-check pull-right"></i>@endif
                        </a>
                    </li>
                    <li>
                        <a href="{{route('adminlteskinchange', 'black')}}">
                            <i class="fas fa-square" style="color: #eeeeee; margin-right: 10px; transform: scale(1.5,1)"></i>  {{ trans('adminlte::skins.white')}} @if(Auth::user()->skin == 'black') <i class="fas fa-check pull-right"></i>@endif
                        </a>
                    </li>
                    <li>
                        <a href="{{route('adminlteskinchange', 'purple')}}">
                            <i class="fas fa-square" style="color: #605ca8; margin-right: 10px; transform: scale(1.5,1)"></i> {{ trans('adminlte::skins.purple')}} @if(Auth::user()->skin == 'purple') <i class="fas fa-check pull-right"></i>@endif
                        </a>
                    </li>
                    <li>
                        <a href="{{route('adminlteskinchange', 'green')}}">
                            <i class="fas fa-square" style="color: #00a65a; margin-right: 10px; transform: scale(1.5,1)"></i> {{ trans('adminlte::skins.green')}} @if(Auth::user()->skin == 'green') <i class="fas fa-check pull-right"></i>@endif
                        </a>
                    </li>
                    <li>
                        <a href="{{route('adminlteskinchange', 'red')}}">
                            <i class="fas fa-square" style="color: #dd4b39; margin-right: 10px; transform: scale(1.5,1)"></i> {{ trans('adminlte::skins.red')}} @if(Auth::user()->skin == 'red') <i class="fas fa-check pull-right"></i>@endif
                        </a>
                    </li>
                    <li>
                        <a href="{{route('adminlteskinchange', 'yellow')}}">
                            <i class="fas fa-square" style="color: #f39c12; margin-right: 10px; transform: scale(1.5,1)"></i> {{ trans('adminlte::skins.yellow')}} @if(Auth::user()->skin == 'yellow') <i class="fas fa-check pull-right"></i>@endif
                        </a>
                    </li>
                </ul>
            </li>
            <li class="header">{{ trans('adminlte::skins.choose_menu')}}</li>
            <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                    <li>
                        <a href="{{route('adminlteskinchange', 'dark')}}">
                            <i class="fas fa-square" style="color: #333333; margin-right: 10px; transform: scale(1.5,1)"></i> {{ trans('adminlte::skins.dark')}} @if(!Auth::user()->skin_light) <i class="fas fa-check pull-right"></i>@endif
                        </a>
                    </li>
                    <li>
                        <a href="{{route('adminlteskinchange', 'light')}}">
                            <i class="fas fa-square" style="color: #eeeeee; margin-right: 10px; transform: scale(1.5,1)"></i> {{ trans('adminlte::skins.light')}} @if(Auth::user()->skin_light) <i class="fas fa-check pull-right"></i>@endif
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
@endif
