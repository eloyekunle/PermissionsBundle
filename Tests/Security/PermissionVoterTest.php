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

use Eloyekunle\PermissionsBundle\Security\PermissionsVoter;
use Eloyekunle\PermissionsBundle\Tests\TestUser;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Yaml\Parser;

class PermissionVoterTest extends TestCase
{
    /**
     * @var TokenInterface
     */
    protected $token;

    protected function setUp()
    {
        $this->token = $this->getMockBuilder(
            'Symfony\Component\Security\Core\Authentication\Token\TokenInterface'
        )->getMock();
    }

    public function getTests()
    {
        return array(
            array(
                array('Delete Users'),
                VoterInterface::ACCESS_ABSTAIN,
                new \StdClass(),
                'Access Abstain if permission is not declared in modules.',
            ),
        );
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
        $voter = $this->createPermissionsVoter();

        $this->assertSame($expectedVote, $voter->vote($this->token, $subject, $attributes), $message);

//        $this->token->setUser();
    }

    protected function getModules()
    {
        $yaml = <<<EOF
name: Sample Permissions

permissions:
    manage systems:
        title: 'Manage Systems'
    view reports:
        title: 'View Reports'
EOF;
        $parser = new Parser();

        return $parser->parse($yaml);
    }

    private function createPermissionsVoter()
    {
        $accessDecisionManager = $this->getMockBuilder(
            'Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface'
        )->getMock();
        $moduleHandler = $this->getMockBuilder('Eloyekunle\PermissionsBundle\Util\PermissionsHandlerInterface')->getMock();

        return new PermissionsVoter($accessDecisionManager, $moduleHandler);
    }

    private function createMockRole()
    {
    }

    private function createMockUser()
    {
        $user = new TestUser();
    }
}
