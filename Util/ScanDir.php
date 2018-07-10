<?php

/*
 * This file is part of the EloyekunlePermissionsBundle package.
 *
 * (c) Elijah Oyekunle <https://elijahoyekunle.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloyekunle\PermissionsBundle\Util;

class ScanDir
{
    private static $directories;
    private static $files;
    private static $extFilter;
    private static $recursive;

    /**
     * scan(dirpath::string|array, extensions::string|array, recursive::true|false).
     */
    public static function scan()
    {
        // Initialize defaults
        self::$recursive = false;
        self::$directories = array();
        self::$files = array();
        self::$extFilter = false;

        // Check we have minimum parameters
        if (!$args = func_get_args()) {
            die('Must provide a path string or array of path strings');
        }
        if ('string' !== gettype($args[0]) && 'array' !== gettype($args[0])) {
            die('Must provide a path string or array of path strings');
        }

        // Check if recursive scan | default action: no sub-directories
        if (isset($args[2]) && true === $args[2]) {
            self::$recursive = true;
        }

        // Was a filter on file extensions included? | default action: return all file types
        if (isset($args[1])) {
            if ('array' === gettype($args[1])) {
                self::$extFilter = array_map('strtolower', $args[1]);
            } elseif ('string' === gettype($args[1])) {
                self::$extFilter[] = strtolower($args[1]);
            }
        }

        // Grab path(s)
        self::verifyPaths($args[0]);

        return self::$files;
    }

    private static function verifyPaths($paths)
    {
        $pathErrors = array();
        if ('string' === gettype($paths)) {
            $paths = array($paths);
        }

        foreach ($paths as $path) {
            if (is_dir($path)) {
                self::$directories[] = $path;
            } else {
                $pathErrors[] = $path;
            }
        }

        if ($pathErrors) {
            echo 'The following directories do not exists<br />';
            die(var_dump($pathErrors));
        }
    }

    /**
     * This is how we scan directories.
     *
     * @param string $dir
     */
    private static function findContents($dir)
    {
        $result = array();
        $root = scandir($dir);
        foreach ($root as $value) {
            if ('.' === $value || '..' === $value) {
                continue;
            }
            if (is_file($dir.DIRECTORY_SEPARATOR.$value)) {
                if (!self::$extFilter || in_array(
                        strtolower(pathinfo($dir.DIRECTORY_SEPARATOR.$value, PATHINFO_EXTENSION)),
                        self::$extFilter
                    )) {
                    self::$files[] = $result[] = $dir.DIRECTORY_SEPARATOR.$value;
                }
                continue;
            }
            if (self::$recursive) {
                foreach (self::findContents($dir.DIRECTORY_SEPARATOR.$value) as $value) {
                    self::$files[] = $result[] = $value;
                }
            }
        }

        // Return required for recursive search
        return $result;
    }
}
