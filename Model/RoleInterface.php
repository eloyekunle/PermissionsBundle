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

interface RoleInterface
{
    /**
     * Role ID for anonymous users; should match what's in the "role" table.
     */
    const ROLE_DEFAULT = 'ROLE_USER';

    /**
     * Role ID for super admin users; should match what's in the "role" table.
     */
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    /**
     * @return int
     */
    public function getId();

    /**
     * @return null|string
     */
    public function getRole();

    /**
     * @param null|string $name
     */
    public function setRole($name);

    /**
     * Returns a list of permissions assigned to the role.
     *
     * @return array
     *               The permissions assigned to the role
     */
    public function getPermissions();

    /**
     * Sets the permissions of a role.
     * This overwrites all previous roles.
     *
     * @param array $permissions
     */
    public function setPermissions(array $permissions);

    /**
     * Checks if the role has a permission.
     *
     * @param string $permission
     *                           The permission to check for
     *
     * @return bool
     *              TRUE if the role has the permission, FALSE if not
     */
    public function hasPermission($permission);

    /**
     * Grant permissions to the role.
     *
     * @param string $permission
     *                           The permission to grant
     *
     * @return $this
     */
    public function grantPermission($permission);

    /**
     * Revokes a permissions from the user role.
     *
     * @param string $permission
     *                           The permission to revoke
     *
     * @return $this
     */
    public function revokePermission($permission);

    /**
     * Indicates that a role has all available permissions.
     *
     * @return bool
     *              TRUE if the role has all permissions
     */
    public function isSuperAdmin();
}
