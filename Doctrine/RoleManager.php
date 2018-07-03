<?php

/*
 * This file is part of the EloyekunlePermissionsBundle package.
 *
 * (c) Elijah Oyekunle <https://elijahoyekunle.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloyekunle\PermissionsBundle\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Eloyekunle\PermissionsBundle\Model\RoleInterface;
use Eloyekunle\PermissionsBundle\Model\RoleManager as BaseRoleManager;

class RoleManager extends BaseRoleManager
{
    /** @var ObjectManager */
    protected $objectManager;

    /** @var string */
    protected $class;

    /** @var ObjectRepository */
    protected $repository;

    public function __construct(ObjectManager $om, $class)
    {
        $this->objectManager = $om;
        $this->repository = $om->getRepository($class);

        $metadata = $om->getClassMetadata($class);
        $this->class = $metadata->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function findRoles()
    {
        return $this->repository->findAll();
    }

    /**
     * {@inheritdoc}
     */
    public function isPermissionInRoles($permission, array $roles)
    {
        $hasPermission = false;

        foreach ($roles as $roleName) {
            /** @var \Eloyekunle\PermissionsBundle\Model\Role $role */
            $role = $this->repository->findOneBy(['name' => $roleName]);

            if ($role->isSuperAdmin() || $role->hasPermission($permission)) {
                $hasPermission = true;
                break;
            }
        }

        return $hasPermission;
    }

    /**
     * {@inheritdoc}
     */
    public function getClass()
    {
        if (false !== strpos($this->class, ':')) {
            $metadata = $this->objectManager->getClassMetadata($this->class);
            $this->class = $metadata->getName();
        }

        return $this->class;
    }

    /**
     * {@inheritdoc}
     */
    public function updateRole(RoleInterface $role, $andFlush = true)
    {
        $this->objectManager->persist($role);

        if ($andFlush) {
            $this->objectManager->flush();
        }
    }
}
