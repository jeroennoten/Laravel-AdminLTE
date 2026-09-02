<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Widget;

class ProfileColItem extends ProfileItem
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $title = null, $text = null, $icon = null, $size = 4,
        $badge = null, $url = null, $urlTarget = 'title',
        $textTooltip = null
    ) {
        parent::__construct(
            $title, $text, $icon, $size, $badge, $url, $urlTarget, $textTooltip
        );
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('adminlte::components.widget.profile-col-item');
    }
}
