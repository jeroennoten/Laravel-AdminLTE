@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

<div class="preloader flex-column justify-content-center align-items-center">

    <img class="animation__shake" src="{{ asset(config('adminlte.preloader_image', 'vendor/adminlte/dist/img/AdminLTELogo.png')) }}"
         alt="{{ asset(config('adminlte.preloader_alt', 'AdminLTE'))}}"
         width="{{ config('adminlte.preloader_img_width', '60')}}"
         height="{{ config('adminlte.preloader_img_height', '60')}}" >
</div>
