<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Widget;

use Illuminate\View\Component;
use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;

class DirectChatMsg extends Component
{
    /**
     * The name of the author of the message.
     *
     * @var string
     */
    public $name;

    /**
     * The timestamp of the message.
     *
     * @var string
     */
    public $timestamp;

    /**
     * The avatar of the author of the message.
     *
     * @var string
     */
    public $img;

    /**
     * Indicates the message is an outgoing one. The AdminLTE v4 stylesheet
     * mirrors the whole entry with the '.end' modifier: the avatar and the
     * bubble arrow move to the opposite side, and the bubble is painted with
     * the theme color of the enclosing direct chat.
     *
     * @var bool|mixed
     */
    public $end;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $name = null, $timestamp = null, $img = null, $end = null
    ) {
        $this->name = UtilsHelper::applyHtmlEntityDecoder($name);
        $this->timestamp = UtilsHelper::applyHtmlEntityDecoder($timestamp);
        $this->img = $img;
        $this->end = $end;
    }

    /**
     * Make the class attribute for the message wrapper.
     *
     * @return string
     */
    public function makeMsgClass()
    {
        $classes = ['direct-chat-msg'];

        if ($this->end) {
            $classes[] = 'end';
        }

        return implode(' ', $classes);
    }

    /**
     * Make the class attribute for the name of the author. The name and the
     * timestamp sit on the same line and swap their sides on an outgoing
     * message, so the name always stands over the avatar.
     *
     * @return string
     */
    public function makeNameClass()
    {
        return 'direct-chat-name float-'.($this->end ? 'end' : 'start');
    }

    /**
     * Make the class attribute for the timestamp of the message. It always
     * floats to the side opposed to the one taken by the name.
     *
     * @return string
     */
    public function makeTimestampClass()
    {
        return 'direct-chat-timestamp float-'.($this->end ? 'start' : 'end');
    }

    /**
     * Check if the informations line of the message is empty (no name and no
     * timestamp defined for the message).
     *
     * @return bool
     */
    public function isInfosEmpty()
    {
        return empty($this->name) && empty($this->timestamp);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('adminlte::components.widget.direct-chat-msg');
    }
}
