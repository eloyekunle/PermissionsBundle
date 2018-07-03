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
    protected $roles;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function addRole($role)
    {
        $role = strtoupper($role);
        if (Role::ROLE_DEFAULT === $role) {
            return $this;
        }

        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
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
    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    /**
     * {@inheritdoc}
     */
    public function isSuperAdmin()
    {
        return $this->hasRole(Role::ROLE_SUPER_ADMIN);
    }

    /**
     * {@inheritdoc}
     */
    public function removeRole($role)
    {
        if (false !== $key = array_search(
            strtoupper($role),
            $this->roles,
            true
          )) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setSuperAdmin($boolean)
    {
        if (true === $boolean) {
            $this->addRole(Role::ROLE_SUPER_ADMIN);
        } else {
            $this->removeRole(Role::ROLE_SUPER_ADMIN);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setRoles(array $roles)
    {
        $this->roles = [];

        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }

    public function hasPermission($permission)
    {
        $hasPermission = false;

        foreach ($this->getRoleEntities() as $role) {
            if ($role->isSuperAdmin() || $role->hasPermission($permission)) {
                $hasPermission = true;
                break;
            }
        }

        return $hasPermission;
    }

    private function getRoleNames()
    {
        $roleNames = [];
        $roles = $this->getRoleEntities();

        foreach ($roles as $role) {
            $roleNames[] = $role->getName();
        }

        return array_unique($roleNames);
    }

    /**
     * @return Role[]
     */
    private function getRoleEntities()
    {
        $roles = $this->roles;

        return $roles;
    }
}
