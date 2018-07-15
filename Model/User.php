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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Storage agnostic user object.
 */
abstract class User implements UserInterface
{
    /**
     * @var RoleInterface[]|Collection
     */
    protected $userRoles;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->userRoles = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        $roleNames = [];
        $roles = $this->getUserRoles();

        foreach ($roles as $role) {
            $roleNames[] = $role->getRole();
        }

        return array_unique($roleNames);
    }

    /**
     * {@inheritdoc}
     */
    public function hasRole(RoleInterface $role)
    {
        return $this->userRoles->contains($role);
    }

    /**
     * {@inheritdoc}
     */
    public function isSuperAdmin()
    {
        $isSuperAdmin = false;
        foreach ($this->getUserRoles() as $role) {
            if ($role->isSuperAdmin()) {
                return true;
            }
        }

        return $isSuperAdmin;
    }

    /**
     * {@inheritdoc}
     */
    public function removeRole(RoleInterface $role)
    {
        if (!$this->userRoles->contains($role)) {
            return;
        }

        $this->userRoles->removeElement($role);
    }

    /**
     * {@inheritdoc}
     */
    public function addRole(RoleInterface $role)
    {
        if ($this->userRoles->contains($role)) {
            return;
        }

        $this->userRoles->add($role);
    }

    /**
     * {@inheritdoc}
     */
    public function setUserRoles(array $userRoles)
    {
        foreach ($userRoles as $role) {
            $this->addRole($role);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasPermission($permission)
    {
        $hasPermission = false;

        foreach ($this->getUserRoles() as $role) {
            if ($role->isSuperAdmin() || $role->hasPermission($permission)) {
                return true;
            }
        }

        return $hasPermission;
    }

    /**
     * @return RoleInterface[]
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }
}
