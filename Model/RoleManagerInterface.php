<?php

/*
 * This file is part of the EloyekunlePermissionsBundle package.
 *
 * (c) Elijah Oyekunle <https://elijahoyekunle.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloyekunle\PermissionsBundle\Model;

interface RoleManagerInterface
{
    public function findRoles();

    public function isPermissionInRoles($permission, array $roles);

    /**
     * Creates and empty role.
     *
     * @return RoleInterface
     */
    public function createRole();

    /**
     * Returns the role's fully qualified class name.
     *
     * @return string
     */
    public function getClass();

    /**
     * Updates a role.
     *
     * @param \Eloyekunle\PermissionsBundle\Model\RoleInterface $role
     * @param bool                                              $andFlush
     *
     * @return mixed
     */
    public function updateRole(RoleInterface $role, $andFlush = true);
}