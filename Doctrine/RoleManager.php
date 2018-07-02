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

    public function findRoles()
    {
        return $this->repository->findAll();
    }
}
