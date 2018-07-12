<?php

/*
 * This file is part of the EloyekunlePermissionsBundle package.
 *
 * (c) Elijah Oyekunle <https://elijahoyekunle.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloyekunle\PermissionsBundle\Tests\Security;

use Eloyekunle\PermissionsBundle\Model\RoleInterface;
use Eloyekunle\PermissionsBundle\Security\PermissionsVoter;
use Eloyekunle\PermissionsBundle\Tests\TestRole;
use Eloyekunle\PermissionsBundle\Tests\TestUser;
use Eloyekunle\PermissionsBundle\Util\PermissionsHandler;
use Eloyekunle\PermissionsBundle\Util\PermissionsHandlerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManager;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\RoleVoter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class PermissionVoterTest extends TestCase
{
    /** @var TokenInterface */
    protected $token;

    /** @var PermissionsVoter */
    protected $voter;

    /** @var AccessDecisionManagerInterface */
    protected $accessDecisionManager;

    /** @var PermissionsHandlerInterface */
    protected $permissionsHandler;

    protected function setUp()
    {
        $this->accessDecisionManager = new AccessDecisionManager([new RoleVoter()]);
        $this->token = $this->getMockBuilder('Symfony\Component\Security\Core\Authentication\Token\TokenInterface')->getMock();
        $this->permissionsHandler = new PermissionsHandler(__DIR__.'/../Fixtures');

        $this->voter = $this->createPermissionsVoter();
    }

    public function getTests()
    {
        return [
            [['delete users'], VoterInterface::ACCESS_DENIED, new \StdClass(), ''],
            [['review articles'], VoterInterface::ACCESS_ABSTAIN, new \StdClass(), 'Access Abstain if permission is not declared in modules.'],
        ];
    }

    public function getTestsNew()
    {
        return [
            [['delete users'], new \StdClass()],
            [['review articles'], new \StdClass(), VoterInterface::ACCESS_ABSTAIN],
        ];
    }

    /**
     * @dataProvider getTests
     *
     * @param array $attributes
     * @param $expectedVote
     * @param $subject
     * @param $message
     */
    public function testVote(array $attributes, $expectedVote, $subject, $message)
    {
        $voter = new PermissionsVoter(new AccessDecisionManager(), $this->permissionsHandler);
        $token = $this->getMockBuilder('Symfony\Component\Security\Core\Authentication\Token\TokenInterface')->getMock();

        $this->assertSame($expectedVote, $voter->vote($token, $subject, $attributes), $message);

        if (VoterInterface::ACCESS_ABSTAIN !== $expectedVote) {
            $role = $this->createMockRole();
            foreach ($attributes as $attribute) {
                $role->grantPermission($attribute);
            }
            $user = $this->createMockUser();
            $user->addRole($role);
            $token->expects($this->atLeastOnce())
                ->method('getUser')
                ->willReturn($user);

            $this->assertSame(VoterInterface::ACCESS_GRANTED, $voter->vote($token, $subject, $attributes));

            foreach ($attributes as $attribute) {
                $role->revokePermission($attribute);
            }

            $this->assertSame(VoterInterface::ACCESS_DENIED, $voter->vote($token, $subject, $attributes));
        }
    }

    /**
     * @dataProvider getTestsNew
     *
     * @param array $attributes
     * @param $subject
     * @param $expectedVote
     */
    public function testSuperAdminPass(array $attributes, $subject, $expectedVote = VoterInterface::ACCESS_GRANTED)
    {
        $this->assertSame($expectedVote, $this->voter->vote($this->getToken([RoleInterface::ROLE_SUPER_ADMIN]), $subject, $attributes));
    }

    protected function createPermissionsVoter()
    {
        return new PermissionsVoter($this->accessDecisionManager, $this->permissionsHandler);
    }

    protected function getToken(array $roles)
    {
        foreach ($roles as $i => $role) {
            $roles[$i] = $this->createMockRole()->setRole($role);
        }
        $token = $this->getMockBuilder('Symfony\Component\Security\Core\Authentication\Token\TokenInterface')->getMock();
        $token->expects($this->any())
            ->method('getRoles')
            ->will($this->returnValue($roles));

        return $token;
    }

    protected function createMockRole()
    {
        return new TestRole();
    }

    protected function createMockUser()
    {
        return new TestUser();
    }
}
