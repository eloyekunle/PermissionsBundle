<?php

/*
 * This file is part of the EloyekunlePermissionsBundle package.
 *
 * (c) Elijah Oyekunle <https://elijahoyekunle.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloyekunle\PermissionsBundle\Permission;

use Eloyekunle\PermissionsBundle\Util\YamlDiscovery;

class ModuleHandler implements ModuleHandlerInterface
{
    /** @var string */
    protected $definitionsPath;

    public function __construct(string $definitionsPath)
    {
        $this->definitionsPath = $definitionsPath;
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleList(): array
    {
        $modules = [];
        $definitionsFiles = YamlDiscovery::getFilesInPath(
          $this->definitionsPath
        );

        foreach ($definitionsFiles as $definitionsFile) {
            $module = YamlDiscovery::decode($definitionsFile);
            $moduleName = $module['name'];
            $modules[] = [
              'key' => basename($definitionsFile, '.yaml'),
              'name' => $moduleName,
              'permissions' => $this->parsePermissions($module['permissions']),
            ];
        }

        return $modules;
    }

    public function getModuleNames()
    {
    }

    public function getPermissions()
    {
        $permissions = [];
        $modules = $this->getModuleList();
        foreach ($modules as $module) {
            foreach ($module['permissions'] as $permission) {
                $permissions[] = [
                  'key' => $permission['key'],
                    'provider' => $module['name'],
                ];
            }
        }

        return $permissions;
    }

    private function parsePermissions(array $permissions): array
    {
        $multiDimPermissions = [];
        foreach ($permissions as $key => $value) {
            $multiDimPermissions[] = ['key' => $key] + $value;
        }

        return $multiDimPermissions;
    }
}
