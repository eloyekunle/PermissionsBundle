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
     * @var Role[]|Collection
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
        return $this->getRoleNames();
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
        return in_array(RoleInterface::ROLE_SUPER_ADMIN, $this->getRoles());
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
        $this->userRoles = new ArrayCollection($userRoles);
    }

    /**
     * {@inheritdoc}
     */
    public function hasPermission($permission)
    {
        $hasPermission = false;

        foreach ($this->getUserRoles() as $role) {
            if ($role->isSuperAdmin() || $role->hasPermission($permission)) {
                $hasPermission = true;
                break;
            }
        }

        return $hasPermission;
    }

    /**
     * @return Role[]
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }

    private function getRoleNames()
    {
        $roleNames = [];
        $roles = $this->getUserRoles();

        foreach ($roles as $role) {
            $roleNames[] = $role->getRole();
        }

        return array_unique($roleNames);
    }
}
