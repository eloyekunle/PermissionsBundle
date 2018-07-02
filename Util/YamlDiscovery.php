<?php

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
