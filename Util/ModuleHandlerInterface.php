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

interface ModuleHandlerInterface
{
    /**
     * Returns the list of currently active modules with their permissions.
     *
     * @return array
     */
    public function getModuleList();

    /**
     * Gets all available permissions.
     *
     * @return array
     */
    public function getPermissions();

    /**
     * Returns all module names.
     *
     * @return string[]
     *                  Returns the human readable names of all modules keyed by machine name
     */
    public function getModuleNames();

    /**
     * Transforms all permissions into ["permission1", "permission2"].
     *
     * @return array
     */
    public function getPermissionsArray();
}
