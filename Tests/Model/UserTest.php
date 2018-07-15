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
use Eloyekunle\PermissionsBundle\Model\UserInterface;
use Eloyekunle\PermissionsBundle\Tests\TestRole;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testTrueHasRole()
    {
        $user = $this->getUser();
        $newrole = $this->createMockRole('ROLE_X');

        $user->addRole($newrole);
        $this->assertTrue($user->hasRole($newrole));

        // Role already exists (added above), so addRole should return without doing anything
        $user->addRole($newrole);
        $this->assertTrue($user->hasRole($newrole));

        $user->removeRole($newrole);
        $this->assertFalse($user->hasRole($newrole));

        // Role already removed (removed above), so removeRole should return without doing anything
        $user->removeRole($newrole);
        $this->assertFalse($user->hasRole($newrole));
    }

    public function testFalseHasRole()
    {
        $user = $this->getUser();
        $newrole = $this->createMockRole('ROLE_X');

        $this->assertFalse($user->hasRole($newrole));
        $user->addRole($newrole);
        $this->assertTrue($user->hasRole($newrole));
    }

    public function testIsSuperAdmin()
    {
        $user = $this->getUser();
        $permission = 'do stuff';

        $this->assertFalse($user->isSuperAdmin());
        $this->assertFalse($user->hasPermission($permission));

        $superAdminRole = $this->createMockRole(RoleInterface::ROLE_SUPER_ADMIN);
        $user->addRole($superAdminRole);

        $this->assertTrue($user->isSuperAdmin());
        $this->assertTrue($user->hasPermission($permission));
    }

    public function testSetUserRoles()
    {
        $roleNames = ['Content Admin', 'Service Admin'];
        $roles = [];
        foreach ($roleNames as $roleName) {
            $roles[] = $this->createMockRole($roleName);
        }
        $user1 = $this->getUser();
        $user2 = $this->getUser();
        $user2->setUserRoles($roles);

        foreach ($roles as $role) {
            $this->assertFalse($user1->hasRole($role));
            $this->assertTrue($user2->hasRole($role));
        }

        $this->assertSame($roleNames, $user2->getRoles());
    }

    /**
     * @param $roleName
     *
     * @return RoleInterface
     */
    protected function createMockRole($roleName)
    {
        return new TestRole($roleName);
    }

    /**
     * @return UserInterface
     *
     * @throws \ReflectionException
     */
    protected function getUser()
    {
        return $this->getMockForAbstractClass('Eloyekunle\PermissionsBundle\Model\User');
    }
}
