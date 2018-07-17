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

class PermissionsHandler implements PermissionsHandlerInterface
{
    /** @var string */
    protected $definitionsPath;

    /** @var array */
    protected $modules;

    /** @var Finder */
    protected $finder;

    /** @var Parser */
    protected $parser;

    /** @var array */
    protected $permissions;

    public function __construct($definitionsPath)
    {
        $this->definitionsPath = $definitionsPath;
        $this->finder = Finder::create()->files()->name('*.yaml')->in($this->definitionsPath);
        $this->parser = new Parser();

        $this->buildPermissionsYaml();
    }

    /**
     * {@inheritdoc}
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * Builds the list of currently active modules with their permissions.
     */
    protected function buildPermissionsYaml()
    {
        $permissions = [];

        foreach ($this->finder as $definitionsFile) {
            $module = $this->parser->parseFile($definitionsFile->getPathname());
            $moduleKey = $definitionsFile->getBasename('.yaml');

            foreach ($module['permissions'] as $key => $permission) {
                $permissions[$key] = $permission + ['provider' => $moduleKey];
            }
        }

        $this->permissions = $permissions;
    }
}
