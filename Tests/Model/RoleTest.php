<?php

/*
 * This file is part of the EloyekunlePermissionsBundle package.
 *
 * (c) Elijah Oyekunle <https://elijahoyekunle.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloyekunle\PermissionsBundle\Tests\Model;

use Eloyekunle\PermissionsBundle\Model\Role;
use Eloyekunle\PermissionsBundle\Model\RoleInterface;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    public function testName()
    {
        $role = $this->getRole();
        $this->assertNull($role->getName());

        $role->setName('System Administrator');
        $this->assertSame('System Administrator', $role->getName());
    }

    public function testGrantPermission()
    {
        $role = $this->getRole();
        $this->assertFalse($role->hasPermission('Administer Systems'));

        $role->grantPermission('Administer Systems');
        $this->assertTrue($role->hasPermission('Administer Systems'));
    }

    public function testRevokePermission()
    {
        $role = $this->getRole();
        $role->grantPermission('Administer Systems');
        $this->assertTrue($role->hasPermission('Administer Systems'));

        $role->revokePermission('Administer Systems');
        $this->assertFalse($role->hasPermission('Administer Systems'));
    }

    public function testSuperAdminPermissions()
    {
        $role = $this->getRole();
        $this->assertFalse($role->hasPermission('Administer Systems'));

        $role->setName(RoleInterface::ROLE_SUPER_ADMIN);
        $this->assertTrue($role->hasPermission('Administer Systems'));

        $role->setName('Not Admin');
        $this->assertFalse($role->hasPermission('Administer Systems'));
    }

    /**
     * @return Role
     *
     * @throws \ReflectionException
     */
    protected function getRole()
    {
        return $this->getMockForAbstractClass('Eloyekunle\PermissionsBundle\Model\Role');
    }
}
