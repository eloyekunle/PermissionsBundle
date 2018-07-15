<?php

/*
 * This file is part of the EloyekunlePermissionsBundle package.
 *
 * (c) Elijah Oyekunle <https://elijahoyekunle.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloyekunle\PermissionsBundle\Tests\Doctrine;

use Eloyekunle\PermissionsBundle\Doctrine\RoleManager;
use Eloyekunle\PermissionsBundle\Model\RoleManagerInterface;
use PHPUnit\Framework\TestCase;

class RoleManagerTest extends TestCase
{
    const ROLE_CLASS = 'Eloyekunle\PermissionsBundle\Tests\TestRole';

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $repository;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $om;

    /** @var RoleManagerInterface */
    protected $roleManager;

    public function setUp()
    {
        if (!interface_exists('Doctrine\Common\Persistence\ObjectManager')) {
            $this->markTestSkipped('Doctrine Common has to be installed for this test to run.');
        }

        $class = $this->getMockBuilder('Doctrine\Common\Persistence\Mapping\ClassMetadata')->getMock();
        $this->om = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')->getMock();
        $this->repository = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectRepository')->getMock();

        $this->om->expects($this->any())
            ->method('getRepository')
            ->with($this->equalTo(static::ROLE_CLASS))
            ->will($this->returnValue($this->repository));
        $this->om->expects($this->any())
            ->method('getClassMetadata')
            ->with($this->equalTo(static::ROLE_CLASS))
            ->will($this->returnValue($class));
        $class->expects($this->any())
            ->method('getName')
            ->will($this->returnValue(static::ROLE_CLASS));

        $this->roleManager = new RoleManager($this->om, static::ROLE_CLASS);
    }

    public function testGetClass()
    {
        $this->assertSame(static::ROLE_CLASS, $this->roleManager->getClass());
    }

    public function testFindRoles()
    {
        $this->repository->expects($this->once())->method('findAll');

        $this->roleManager->findRoles();
    }

    public function testFindRoleBy()
    {
        $crit = ['name' => 'finestRole'];
        $this->repository->expects($this->once())->method('findOneBy')->with($crit);

        $this->roleManager->findRoleBy($crit);
    }

    public function testFindRole()
    {
        $id = 10;
        $this->repository->expects($this->once())->method('find')->with($id);

        $this->roleManager->findRole($id);
    }

    public function testUpdateRole()
    {
        $role = $this->getRole();
        $this->om->expects($this->once())->method('persist')->with($role);
        $this->om->expects($this->once())->method('flush');

        $this->roleManager->updateRole($role);
    }

    public function testDeleteRole()
    {
        $role = $this->getRole();
        $this->om->expects($this->once())->method('remove')->with($role);
        $this->om->expects($this->once())->method('flush');

        $this->roleManager->deleteRole($role);
    }

    protected function getRole()
    {
        return $this->roleManager->createRole();
    }
}
