<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Layout;

use Illuminate\View\Component;

class NavbarCustomMenu extends Component
{
    /**
     * Extra classes for the inner "navbar-nav" element. This provides a way to
     * customize the navigation container style.
     *
     * @var string
     */
    public $navClass;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($navClass = null)
    {
        $this->navClass = $navClass;
    }

    /**
     * Make the class attribute for the wrapper.
     *
     * @return string
     */
    public function makeWrapperClass()
    {
        return 'navbar-custom-menu';
    }

    /**
     * Make the class attribute for the inner navigation container.
     *
     * @return string
     */
    public function makeNavClass()
    {
        $classes = ['navbar-nav'];

        if (isset($this->navClass)) {
            $classes[] = $this->navClass;
        }

        return implode(' ', $classes);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('adminlte::components.layout.navbar-custom-menu');
    }
}
