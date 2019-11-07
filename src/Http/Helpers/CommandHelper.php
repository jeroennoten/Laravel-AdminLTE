<?php

namespace JeroenNoten\LaravelAdminLte\Http\Helpers;

class CommandHelper
{
    /**
     * Check if the directories for the files exists.
     *
     * @param $directory
     * @return void
     */
    public static function ensureDirectoriesExist($directory)
    {
        // CHECK if directory exists, if not create it
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
    }

    /**
     * Recursive function that copies an entire directory to a destination.
     *
     * @param $source_directory
     * @param $destination_directory
     */
    public static function directoryCopy($source_directory, $destination_directory, $force = false, $recursive = false, $ignore = [], $ignore_ending = null)
    {
        //Checks destination folder existance
        self::ensureDirectoriesExist($destination_directory);
        //Open source directory
        $directory = opendir($source_directory);

        while (false !== ($file = readdir($directory))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($source_directory.'/'.$file) && $recursive) {
                    self::directoryCopy($source_directory.'/'.$file, $destination_directory.'/'.$file, $force, $recursive, $ignore, $ignore_ending);
                } elseif (! is_dir($source_directory.'/'.$file)) {
                    $checkup = true;

                    if ($ignore_ending) {
                        if (! is_array($ignore_ending)) {
                            $ignore_ending = str_replace('*', '', $ignore_ending);

                            $checkup = (substr($file, -strlen($ignore_ending)) !== $ignore_ending);
                        } else {
                            foreach ($ignore_ending as $key => $ignore_ending_sub) {
                                if ($checkup) {
                                    $ignore_ending_sub = str_replace('*', '', $ignore_ending_sub);

                                    $checkup = (substr($file, -strlen($ignore_ending_sub)) !== $ignore_ending_sub);
                                }
                            }
                        }
                    }

                    if ($checkup && (! in_array($file, $ignore))) {
                        if (file_exists($destination_directory.'/'.$file) && ! $force) {
                            continue;
                        }
                        copy($source_directory.'/'.$file, $destination_directory.'/'.$file);
                    }
                }
            }
        }

        closedir($directory);
    }

    /**
     * Compare Directories.
     *
     * @return void
     */
    public static function compareDirectories($package_path, $assets_path, $sub_folder = '', $ignore = [], $ignore_ending = [], $recursive = false, $internal = null)
    {
        $return = [];
        $package_sha1 = '';
        $assets_sha1 = '';

        $handle = opendir($package_path);

        while ($file = readdir($handle)) {
            $checkup = true;

            if (in_array($file, $ignore)) {
                $checkup = false;
            } elseif ($ignore_ending) {
                if (! is_array($ignore_ending)) {
                    $ignore_ending = str_replace('*', '', $ignore_ending);

                    $checkup = (substr($file, -strlen($ignore_ending)) !== $ignore_ending);
                } else {
                    foreach ($ignore_ending as $key => $ignore_ending_sub) {
                        if ($checkup) {
                            $ignore_ending_sub = str_replace('*', '', $ignore_ending_sub);

                            $checkup = (substr($file, -strlen($ignore_ending_sub)) !== $ignore_ending_sub);
                        }
                    }
                }
            }

            if ($file == '.' || $file == '..' || ! $checkup) {
                continue;
            }
            $sourcepath = $package_path.$sub_folder.DIRECTORY_SEPARATOR.$file;
            $destpath = $assets_path.DIRECTORY_SEPARATOR.$file;
            if (is_file($sourcepath)) {
                $package_sha1 .= sha1_file($sourcepath);
                if (file_exists($destpath) && is_file($destpath)) {
                    $assets_sha1 .= sha1_file($destpath);
                }
            } elseif ($recursive) {
                $return = self::compareDirectories($sourcepath, $destpath, '', $ignore, $ignore_ending, $recursive, true);
                $package_sha1 .= $return['package_sha1'];
                $assets_sha1 .= $return['assets_sha1'];
            }
        }

        closedir($handle);

        if ($internal) {
            return [
                'package_sha1' => $package_sha1,
                'assets_sha1' => $assets_sha1,
            ];
        }

        return $package_sha1 == $assets_sha1 ? true : ($assets_sha1 == '' ? null : false);
    }

    /**
     * Rescursive directory remove.
     *
     * @param $directory
     * @return void
     */
    public static function removeDirectory($directory)
    {
        if (file_exists($directory)) {
            foreach (glob($directory.'/{,.}*[!.]', GLOB_MARK | GLOB_BRACE) as $file) {
                is_dir($file) ? self::removeDirectory($file) : unlink($file);
            }
            rmdir($directory);
        }
    }
}
