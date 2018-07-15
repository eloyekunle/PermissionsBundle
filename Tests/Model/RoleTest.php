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

use Eloyekunle\PermissionsBundle\Model\RoleInterface;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    public function testName()
    {
        $role = $this->getRole();
        $this->assertNull($role->getRole());

        $role->setRole('System Administrator');
        $this->assertSame('System Administrator', $role->getRole());
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

        $role->setRole(RoleInterface::ROLE_SUPER_ADMIN);
        $this->assertTrue($role->hasPermission('Administer Systems'));
        $role->grantPermission('Play with Cats');
        $this->assertSame([], $role->getPermissions());
        $role->revokePermission('Play with Cats');
        $this->assertSame([], $role->getPermissions());

        $role->setRole('Not Admin');
        $this->assertFalse($role->hasPermission('Administer Systems'));
    }

    public function testSetPermissions()
    {
        $role = $this->getRole();
        $this->assertFalse($role->hasPermission('Administer Systems'));
        $this->assertFalse($role->hasPermission('View Reports'));

        $permissions = ['Administer Systems', 'View Reports', ''];
        $role->setPermissions($permissions);
        $this->assertTrue($role->hasPermission('Administer Systems'));
        $this->assertTrue($role->hasPermission('View Reports'));
        $this->assertSame(2, count($role->getPermissions()));
    }

    /**
     * @return RoleInterface
     *
     * @throws \ReflectionException
     */
    protected function getRole()
    {
        return $this->getMockForAbstractClass('Eloyekunle\PermissionsBundle\Model\Role');
    }
}
