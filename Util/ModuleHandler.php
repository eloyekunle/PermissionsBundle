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

use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Parser;

class ModuleHandler implements ModuleHandlerInterface
{
    /** @var string */
    protected $definitionsPath;

    /** @var array */
    protected $modules;

    /** @var Finder */
    protected $finder;

    /** @var Parser */
    protected $parser;

    public function __construct($definitionsPath)
    {
        $this->definitionsPath = $definitionsPath;
        $this->modules = $this->getModuleList();
        $this->finder = Finder::create()->files()->name('*.yaml')->in($this->definitionsPath);
        $this->parser = new Parser();
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleList()
    {
        $modules = [];
        $definitionsFiles = $this->finder->getIterator();

        foreach ($definitionsFiles as $definitionsFile) {
            $module = $this->parser->parseFile($definitionsFile);
            $moduleName = $module['name'];
            $modules[] = [
                'key' => basename($definitionsFile, '.yaml'),
                'name' => $moduleName,
                'permissions' => $this->parsePermissions($module['permissions']),
            ];
        }

        return $modules;
    }

    /**
     * {@inheritdoc}
     */
    public function getPermissions()
    {
        $permissions = [];
        foreach ($this->modules as $module) {
            foreach ($module['permissions'] as $permission) {
                $permissions[] = [
                    'key' => $permission['key'],
                    'provider' => $module['name'],
                ];
            }
        }

        return $permissions;
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleNames()
    {
        $modules = [];
        foreach ($this->modules as $moduleId => $module) {
            $modules[$moduleId] = $module['name'];
        }

        return $modules;
    }

    /**
     * Transforms all permissions into ["MODULE_Permission_Name" => "permission_name"].
     *
     * @return array
     */
    public function getPermissionsCanonical()
    {
        $permissions = [];
        foreach ($this->modules as $module) {
            foreach ($module['permissions'] as $permission) {
                $permissions[strtoupper($module['key']).'_'.str_replace(
                    ' ',
                    '_',
                    ucwords($permission['key'])
                )] = $permission['key'];
            }
        }

        return $permissions;
    }

    /**
     * {@inheritdoc}
     */
    public function getPermissionsArray()
    {
        $permissions = [];
        foreach ($this->modules as $module) {
            foreach ($module['permissions'] as $permission) {
                $permissions[] = $permission['key'];
            }
        }

        return $permissions;
    }

    /**
     * Transforms permissions from
     * [
     *  'permission_1' => [
     *      'dependencies'  => 'say hello',
     *      'name'          => 'Permission 1'
     *  ],
     * ...
     * ].
     *
     * to
     *
     * [
     *  [
     *      'key'           => 'permission_1',
     *      'dependencies'  => 'say hello',
     *      'name'          => 'Permission 1'
     *  ],
     * ...
     * ]
     *
     *
     * @param array $permissions
     *
     * @return array
     */
    private function parsePermissions(array $permissions)
    {
        $multiDimPermissions = [];
        foreach ($permissions as $key => $value) {
            $multiDimPermissions[] = ['key' => $key] + $value;
        }

        return $multiDimPermissions;
    }
}
