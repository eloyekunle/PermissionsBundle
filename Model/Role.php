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

abstract class Role implements RoleInterface
{
    /**
     * The machine name of this role.
     *
     * @var mixed
     */
    protected $id;

    /**
     * The human-readable name of this role.
     *
     * @var string
     */
    protected $role;

    /**
     * The permissions belonging to this role.
     *
     * @var array
     */
    protected $permissions;

    public function __construct($role = null)
    {
        $this->role = $role;
        $this->permissions = [];
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    public function getRole()
    {
        return $this->role;
    }

    /**
     * {@inheritdoc}
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPermissions()
    {
        if ($this->isSuperAdmin()) {
            return [];
        }

        return $this->permissions;
    }

    /**
     * {@inheritdoc}
     */
    public function setPermissions(array $permissions)
    {
        $this->permissions = [];

        foreach ($permissions as $permission) {
            if (!$permission) {
                break;
            }
            $this->grantPermission($permission);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasPermission($permission)
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return in_array($permission, $this->getPermissions(), true);
    }

    /**
     * {@inheritdoc}
     */
    public function grantPermission($permission)
    {
        if ($this->isSuperAdmin()) {
            return $this;
        }
        if (!$this->hasPermission($permission)) {
            $this->permissions[] = $permission;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function revokePermission($permission)
    {
        if ($this->isSuperAdmin()) {
            return $this;
        }
        $this->permissions = array_diff($this->permissions, [$permission]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuperAdmin()
    {
        return $this->getRole() === static::ROLE_SUPER_ADMIN;
    }
}
