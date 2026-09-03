<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Widget;

use Illuminate\View\Component;
use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;

class Post extends Component
{
    /**
     * The available user block sizes for the post header.
     *
     * @var array
     */
    protected const SIZES = ['sm'];

    /**
     * The user name of the post author. When provided, the post is prefixed
     * with an AdminLTE user block.
     *
     * @var string
     */
    public $name;

    /**
     * The avatar of the post author.
     *
     * @var string
     */
    public $img;

    /**
     * A short description for the post, usually a timestamp or the context of
     * the entry (for example: "Shared publicly - 7:30 PM today").
     *
     * @var string
     */
    public $description;

    /**
     * An URL for the post author. When defined, the user name is wrapped
     * inside a link.
     *
     * @var string
     */
    public $url;

    /**
     * The size of the user block of the post header (sm). The small size
     * shrinks the avatar and the font sizes, it is the one used on the
     * comments of a feed.
     *
     * @var string
     */
    public $size;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $name = null, $img = null, $description = null, $url = null,
        $size = null
    ) {
        $this->name = UtilsHelper::applyHtmlEntityDecoder($name);
        $this->img = $img;
        $this->description = UtilsHelper::applyHtmlEntityDecoder($description);
        $this->url = $url;
        $this->size = in_array($size, static::SIZES, true) ? $size : null;
    }

    /**
     * Check whether the post provides the data of an author, and hence the
     * user block of the header should be rendered.
     *
     * @return bool
     */
    public function hasAuthor()
    {
        return ! empty($this->name)
            || ! empty($this->img)
            || ! empty($this->description);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('adminlte::components.widget.post');
    }
}
