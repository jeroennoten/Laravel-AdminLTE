<?php

namespace JeroenNoten\LaravelAdminLte\Console\PackageResources;

use Illuminate\Support\Facades\File;
use JeroenNoten\LaravelAdminLte\Helpers\CommandHelper;

class BladeComponentsResource extends PackageResource
{
    /**
     * Create a new package resource instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Fill the resource data.

        $this->description = 'The set of blade components provided by this package';

        $this->source = [
            'classes' => CommandHelper::getPackagePath('src/View/Components'),
            'views' => CommandHelper::getPackagePath('resources/views/components'),
        ];

        $this->target = [
            'classes' => app_path('View/Components/Adminlte'),
            'views' => CommandHelper::getViewPath('vendor/adminlte/components'),
        ];

        $this->required = false;

        // Fill the set of installation messages.

        $this->messages = [
            'install' => 'Do you want to publish the blade component files?',
            'overwrite' => 'Blade components were already published. Want to replace?',
            'success' => 'Blade components files published successfully',
        ];
    }

    /**
     * Installs or publishes the resource.
     *
     * @return void
     */
    public function install()
    {
        // Copy the component classes to the publishing destination.

        CommandHelper::CopyDirectory(
            $this->source['classes'],
            $this->target['classes'],
            true,
            true
        );

        // Copy the component views to the publishing destination.

        CommandHelper::CopyDirectory(
            $this->source['views'],
            $this->target['views'],
            true,
            true
        );

        // Adapt published components classes to the Laravel framework, this
        // will mainly change the component classes namespace.

        $this->adaptPublishedComponentClasses();
    }

    /**
     * Uninstalls the resource.
     *
     * @return void
     */
    public function uninstall()
    {
        // Remove the component classes from the target folder. When
        // component classes does not exists, we consider they as uninstalled.

        if (File::isDirectory($this->target['classes'])) {
            File::deleteDirectory($this->target['classes']);
        }

        // Remove the component views from the target folder. When
        // component views does not exists, we consider they as uninstalled.

        if (File::isDirectory($this->target['views'])) {
            File::deleteDirectory($this->target['views']);
        }
    }

    /**
     * Checks whether the resource already exists in the target location.
     *
     * @return bool
     */
    public function exists()
    {
        return File::isDirectory($this->target['classes'])
            || File::isDirectory($this->target['views']);
    }

    /**
     * Checks whether the resource is correctly installed, i.e. if the source
     * items matches with the items available at the target location.
     *
     * @return bool
     */
    public function installed()
    {
        // Note we can't expect the published component classes to match
        // exactly with the default package ones, since the namespace of the
        // classes are changed during the installation process. So, we'll just
        // control that the number of published component classes matches with
        // the packages default ones.

        $srcClassesNum = count(File::allFiles($this->source['classes']));

        $tgtClassesNum = File::isDirectory($this->target['classes'])
            ? count(File::allFiles($this->target['classes']))
            : 0;

        return $srcClassesNum === $tgtClassesNum
            && CommandHelper::compareDirectories(
                $this->source['views'],
                $this->target['views'],
                true
            );
    }

    /**
     * Adapt the published blade component classes by changing their namespace.
     *
     * @return void
     */
    protected function adaptPublishedComponentClasses()
    {
        // Get an array of all published component classes files.

        $files = File::allFiles($this->target['classes']);

        // Make replacements on each of the collected files.

        foreach ($files as $file) {
            $content = File::get($file->getPathname());

            // Replace the namespace.

            $content = str_replace(
                'namespace JeroenNoten\LaravelAdminLte\View\Components',
                'namespace App\View\Components\Adminlte',
                $content
            );

            // Put the new content in the file.

            File::put($file->getPathname(), $content);
        }
    }
}
