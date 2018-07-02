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

interface ModuleHandlerInterface
{
    /**
     * Returns the list of currently active modules with their permissions.
     *
     * @return array
     */
    public function getModuleList();

    public function getModuleNames();
}
