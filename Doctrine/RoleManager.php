<?php


namespace Eloyekunle\PermissionsBundle\Doctrine;


use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

class RoleManager
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