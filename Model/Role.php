<?php

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
    protected $name;

    /**
     * The weight of this role in administrative listings.
     *
     * @var int
     */
    protected $weight;

    /**
     * The permissions belonging to this role.
     *
     * @var array
     */
    protected $permissions = [];

    /**
     * An indicator whether the role has all permissions.
     *
     * @var bool
     */
    protected $is_admin;

    /**
     * {@inheritdoc}
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
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
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * {@inheritdoc}
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * {@inheritdoc}
     */
    public function hasPermission($permission)
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return in_array($permission, $this->permissions);
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
        return $this->getName() == static::ROLE_SYSTEM_ADMIN;
    }

    /**
     * {@inheritdoc}
     */
    public function setIsSuperAdmin($is_admin)
    {
        $this->is_admin = $is_admin;

        return $this;
    }
}