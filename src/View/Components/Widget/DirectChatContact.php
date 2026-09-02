<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Widget;

use Illuminate\View\Component;
use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;

class DirectChatContact extends Component
{
    /**
     * The name of the contact.
     *
     * @var string
     */
    public $name;

    /**
     * The avatar of the contact.
     *
     * @var string
     */
    public $img;

    /**
     * The date of the last message exchanged with the contact.
     *
     * @var string
     */
    public $date;

    /**
     * An excerpt of the last message exchanged with the contact. The default
     * slot takes precedence over this attribute.
     *
     * @var string
     */
    public $msg;

    /**
     * An URL for the contact. When defined, the whole entry is wrapped inside
     * a link.
     *
     * @var string
     */
    public $url;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $name = null, $img = null, $date = null, $msg = null, $url = null
    ) {
        $this->name = UtilsHelper::applyHtmlEntityDecoder($name);
        $this->img = $img;
        $this->date = UtilsHelper::applyHtmlEntityDecoder($date);
        $this->msg = UtilsHelper::applyHtmlEntityDecoder($msg);
        $this->url = $url;
    }

    /**
     * Check if the contact holds a message excerpt.
     *
     * @param  bool  $hasSlot  Whether the default slot is filled
     * @return bool
     */
    public function hasMsg($hasSlot = false)
    {
        return $hasSlot || ! empty($this->msg);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('adminlte::components.widget.direct-chat-contact');
    }
}
