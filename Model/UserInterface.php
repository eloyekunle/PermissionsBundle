<?php

namespace Eloyekunle\PermissionsBundle\Model;


interface UserInterface
{
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