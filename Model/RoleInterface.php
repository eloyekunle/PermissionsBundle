<?php

namespace Eloyekunle\PermissionsBundle\Model;


interface RoleInterface
{

    /**
     * Role ID for anonymous users; should match what's in the "role" table.
     */
    const ROLE_DEFAULT = 'ROLE_USER';

    /**
     * Role ID for authenticated users; should match what's in the "role" table.
     */
    const ROLE_SYSTEM_ADMIN = 'ROLE_SYSTEM_ADMIN';

    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @return null|string
     */
    public function getName();

    /**
     * @param null|string $name
     */
    public function setName(?string $name);

    /**
     * Returns a list of permissions assigned to the role.
     *
     * @return array
     *   The permissions assigned to the role.
     */
    public function getPermissions();

    /**
     * Checks if the role has a permission.
     *
     * @param string $permission
     *   The permission to check for.
     *
     * @return bool
     *   TRUE if the role has the permission, FALSE if not.
     */
    public function hasPermission($permission);

    /**
     * Grant permissions to the role.
     *
     * @param string $permission
     *   The permission to grant.
     *
     * @return $this
     */
    public function grantPermission($permission);

    /**
     * Revokes a permissions from the user role.
     *
     * @param string $permission
     *   The permission to revoke.
     *
     * @return $this
     */
    public function revokePermission($permission);

    /**
     * Indicates that a role has all available permissions.
     *
     * @return bool
     *   TRUE if the role has all permissions.
     */
    public function isSuperAdmin();

    /**
     * Sets the role to be an admin role.
     *
     * @param bool $is_admin
     *   TRUE if the role should be an admin role.
     *
     * @return $this
     */
    public function setIsSuperAdmin($is_admin);
}