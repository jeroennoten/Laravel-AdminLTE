<?php

namespace JeroenNoten\LaravelAdminLte\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CommandHelper
{
    /**
     * The path to the package's root folder.
     *
     * @var string
     */
    protected static $packagePath = __DIR__.'/../../';

    /**
     * The path to the package's stubs folder.
     *
     * @var string
     */
    protected static $stubsPath = __DIR__.'/../Console/stubs';

    /**
     * Copies a directory to the specified destination. Returns whether the
     * directory could be copied successfully.
     *
     * @param  string  $directory  The path of the directory to be copied
     * @param  string  $destination  The path of the destination folder
     * @param  bool  $force  Whether to force the overwrite of existing files
     * @param  bool  $recursive  Whether to copy subfolders recursively
     * @param  array  $ignores  An array of name patterns to ignore while copying
     * @return bool
     */
    public static function copyDirectory(
        $directory,
        $destination,
        $force = false,
        $recursive = false,
        $ignores = []
    ) {
        // Check if we can open the source folder.

        if (! is_resource($dirHandler = @opendir($directory))) {
            return false;
        }

        // Ensure the destination folder exists.

        File::ensureDirectoryExists($destination);

        // Copy the folder to the destination. Note we will skip dots items.

        $avoids = array_merge($ignores, ['.', '..']);

        while (($item = readdir($dirHandler)) !== false) {
            $s = $directory.DIRECTORY_SEPARATOR.$item;
            $t = $destination.DIRECTORY_SEPARATOR.$item;

            // Check if this item should be copied or ignored.

            if (! self::shouldCopyItem($s, $t, $force, $recursive, $avoids)) {
                continue;
            }

            // Finally, copy the file/folder to destination. If the item is a
            // folder we proceed recursively. Otherwise, copy the file to the
            // destination.

            $res = File::isDirectory($s)
                ? self::copyDirectory($s, $t, $force, $recursive, $ignores)
                : File::copy($s, $t);

            if (! $res) {
                closedir($dirHandler);

                return false;
            }
        }

        closedir($dirHandler);

        return true;
    }

    /**
     * Compares two directories file by file. Returns a boolean indicating
     * whether the two directories are equal, or null when one of the
     * directories does not exists.
     *
     * @param  string  $dir1  The path of the first folder
     * @param  string  $dir2  The path of the second folder
     * @param  bool  $recursive  Whether to compare subfolders recursively
     * @param  array  $ignores  An array of name patterns to ignore while comparing
     * @return bool|null
     */
    public static function compareDirectories(
        $dir1,
        $dir2,
        $recursive = false,
        $ignores = []
    ) {
        // Check if we can open the first folder.

        if (! is_resource($dirHandler = @opendir($dir1))) {
            return;
        }

        // Check if the second folder exists.

        if (! is_dir($dir2)) {
            return;
        }

        // Now, compare the folders. Note we will skip dots items.

        $avoids = array_merge($ignores, ['.', '..']);

        while (($item = readdir($dirHandler)) !== false) {
            $s = $dir1.DIRECTORY_SEPARATOR.$item;
            $t = $dir2.DIRECTORY_SEPARATOR.$item;

            // Check if this item should be compared or ignored.

            if (! self::shouldCompareItem($s, $recursive, $avoids)) {
                continue;
            }

            // Finally, compare the files/folders. If the item to compare is a
            // folder we proceed recursively.

            $res = File::isDirectory($s)
                ? (bool) self::compareDirectories($s, $t, $recursive, $ignores)
                : self::compareFiles($s, $t);

            if (! $res) {
                closedir($dirHandler);

                return false;
            }
        }

        closedir($dirHandler);

        return true;
    }

    /**
     * Checks if two files are equals by comparing their hash values.
     *
     * @param  string  $file1  The path to the first file
     * @param  string  $file2  The path to the second file
     * @return bool
     */
    public static function compareFiles($file1, $file2)
    {
        if (! File::isFile($file1) || ! File::isFile($file2)) {
            return false;
        }

        return File::hash($file1) === File::hash($file2);
    }

    /**
     * Gets the fully qualified path to the specified package resource.
     *
     * @param  string  $path  Relative path to the resource
     * @return string
     */
    public static function getPackagePath($path = null)
    {
        if (empty($path)) {
            return self::$packagePath;
        }

        return self::$packagePath.DIRECTORY_SEPARATOR.$path;
    }

    /**
     * Gets the fully qualified path to the specified package stub resource.
     *
     * @param  string  $path  Relative path to the stub resource
     * @return string
     */
    public static function getStubPath($path = null)
    {
        if (empty($path)) {
            return self::$stubsPath;
        }

        return self::$stubsPath.DIRECTORY_SEPARATOR.$path;
    }

    /**
     * Gets the fully qualified path to the specified view resource, relative to
     * the configured view path.
     *
     * @param  string  $path  Relative path to some view resource
     * @return string
     */
    public static function getViewPath($path = null)
    {
        $basePath = config('view.paths')[0] ?? resource_path('views');

        if (empty($path)) {
            return $basePath;
        }

        return $basePath.DIRECTORY_SEPARATOR.$path;
    }

    /**
     * Checks whether an item (file or folder) should be copied to the
     * specified destination by analyzing the recursive and force flags, and a
     * set of item name patterns to be ignored.
     *
     * @param  string  $s  The source item path
     * @param  string  $t  The target destination path
     * @param  bool  $fFlag  The value of the force flag
     * @param  bool  $rFlag  The value of the recursive flag
     * @param  array  $ignores  An array of name patterns to be ignored
     * @return bool
     */
    protected static function shouldCopyItem($s, $t, $fFlag, $rFlag, $ignores)
    {
        // At first, we should copy the item when it's a directoy and the
        // recursive flag is set, or when it's a file and the target path does
        // not exists or the force flag is set.

        $shouldCopy = File::isDirectory($s)
            ? $rFlag
            : (! File::exists($t) || $fFlag);

        // Then we should also check the item name does not match any of the
        // ignore patterns.

        return $shouldCopy
            && ! self::isIgnoredItem(File::basename($s), $ignores);
    }

    /**
     * Checks whether an item (file or folder) should be compared by analyzing
     * the recursive flag, and a set of item name patterns to be ignored.
     *
     * @param  string  $s  The source item path
     * @param  bool  $rFlag  The value of the recursive flag
     * @param  array  $ignores  An array of name patterns to be ignored
     * @return bool
     */
    protected static function shouldCompareItem($s, $rFlag, $ignores)
    {
        // At first, we should compare the item when it's a file or when it's
        // a directory and the recursive flag is set.

        $shouldCompare = File::isFile($s) || $rFlag;

        // Then we should also check that the item name does not match any of
        // the ignore patterns.

        return $shouldCompare
            && ! self::isIgnoredItem(File::basename($s), $ignores);
    }

    /**
     * Checks if the name of a folder or file belongs to a set of specified
     * patterns to be ignored.
     *
     * @param  string  $name  The name of the folder or file to verify
     * @param  array  $ignores  An array of name patterns to be ignored
     * @return bool
     */
    protected static function isIgnoredItem($name, $ignores)
    {
        foreach ($ignores as $pattern) {
            $match = Str::startsWith($pattern, 'regex:')
                ? preg_match(Str::substr($pattern, 6), $name)
                : Str::is($pattern, $name);

            if ($match) {
                return true;
            }
        }

        return false;
    }
}
