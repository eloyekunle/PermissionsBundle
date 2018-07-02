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

use Symfony\Component\Yaml\Yaml;

class YamlDiscovery
{
    public static function decode(string $filePath)
    {
        return Yaml::parseFile($filePath);
    }

    public static function getFilesInPath(string $path): array
    {
        return ScanDir::scan($path, ['yaml']);
    }
}
