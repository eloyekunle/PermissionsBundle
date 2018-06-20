<?php
/**
 * Copyright (c) 2018. Elijah Oyekunle <eloyekunle@gmail.com>.
 * Please view the LICENSE file for the full copyright and license information.
 */

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