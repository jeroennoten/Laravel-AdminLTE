<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Widget;

use Illuminate\View\Component;
use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;

class UserBlock extends Component
{
    /**
     * The available user block sizes.
     *
     * @var array
     */
    protected const SIZES = ['sm'];

    /**
     * The user name of the block.
     *
     * @var string
     */
    public $name;

    /**
     * The user image of the block.
     *
     * @var string
     */
    public $img;

    /**
     * A short description for the block, usually a timestamp or the context
     * of the entry.
     *
     * @var string
     */
    public $description;

    /**
     * An URL for the block. When defined, the user name is wrapped inside a
     * link.
     *
     * @var string
     */
    public $url;

    /**
     * The user block size (sm). The small size shrinks the avatar and the
     * font sizes, it is the one used on the comments of a feed.
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
        $this->size = $size;
    }

    /**
     * Make the class attribute for the user block item.
     *
     * @return string
     */
    public function makeUserBlockClass()
    {
        $classes = ['user-block'];

        if (isset($this->size) && in_array($this->size, static::SIZES)) {
            $classes[] = "user-block-{$this->size}";
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
        return view('adminlte::components.widget.user-block');
    }
}
