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

use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

interface UserInterface extends BaseUserInterface, \Serializable
{
    /**
     * Returns the user unique id.
     *
     * @return int
     */
    public function getId();

    /**
     * Sets the username.
     *
     * @param string $username
     *
     * @return static
     */
    public function setUsername($username);

    /**
     * Gets email.
     *
     * @return string
     */
    public function getEmail();

    /**
     * Sets the email.
     *
     * @param string $email
     *
     * @return static
     */
    public function setEmail($email);

    /**
     * Gets the plain password.
     *
     * @return string
     */
    public function getPlainPassword();

    /**
     * Sets the plain password.
     *
     * @param string $password
     *
     * @return static
     */
    public function setPlainPassword($password);

    /**
     * Sets the hashed password.
     *
     * @param string $password
     *
     * @return static
     */
    public function setPassword($password);

    /**
     * Tells if the the given user has the super admin role.
     *
     * @return bool
     */
    public function isSuperAdmin();

    /**
     * Checks whether a user has a certain permission.
     *
     * @param string $permission
     *                           The permission string to check
     *
     * @return bool
     *              TRUE if the user has the permission, FALSE otherwise
     */
    public function hasPermission($permission);

    /**
     * Whether a user has a certain role.
     *
     * @param string $role
     *                     The role ID to check
     *
     * @return bool
     *              Returns TRUE if the user has the role, otherwise FALSE
     */
    public function hasRole($role);

    /**
     * Sets the roles of the user.
     *
     * This overwrites any previous roles.
     *
     * @param \Eloyekunle\PermissionsBundle\Model\RoleInterface[] $roles
     *
     * @return static
     */
    public function setUserRoles(array $roles);

    public function getUserRoles();

    /**
     * Add a role to a user.
     *
     * @param \Eloyekunle\PermissionsBundle\Model\RoleInterface $role
     *                                                                The role ID to add
     */
    public function addRole(RoleInterface $role);

    /**
     * Remove a role from a user.
     *
     * @param \Eloyekunle\PermissionsBundle\Model\RoleInterface $role
     *                                                                The role ID to remove
     */
    public function removeRole(RoleInterface $role);
}
