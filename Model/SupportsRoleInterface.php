<?php

namespace Eloyekunle\PermissionsBundle\Model;


interface SupportsRoleInterface
{
    /**
     * Checks whether a user has a certain permission.
     *
     * @param string $permission
     *   The permission string to check.
     *
     * @return bool
     *   TRUE if the user has the permission, FALSE otherwise.
     */
    public function hasPermission($permission);

    /**
     * Whether a user has a certain role.
     *
     * @param string $rid
     *   The role ID to check.
     *
     * @return bool
     *   Returns TRUE if the user has the role, otherwise FALSE.
     */
    public function hasRole($rid);

    /**
     * Sets the roles of the user.
     *
     * This overwrites any previous roles.
     *
     * @param array $roles
     *
     * @return static
     */
    public function setRoles(array $roles);

    /**
     * Add a role to a user.
     *
     * @param string $rid
     *   The role ID to add.
     */
    public function addRole($rid);

    /**
     * Remove a role from a user.
     *
     * @param string $rid
     *   The role ID to remove.
     */
    public function removeRole($rid);
}