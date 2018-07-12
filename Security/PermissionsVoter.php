<?php

/*
 * This file is part of the EloyekunlePermissionsBundle package.
 *
 * (c) Elijah Oyekunle <https://elijahoyekunle.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloyekunle\PermissionsBundle\Security;

use Eloyekunle\PermissionsBundle\Model\RoleInterface;
use Eloyekunle\PermissionsBundle\Model\UserInterface;
use Eloyekunle\PermissionsBundle\Util\PermissionsHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PermissionsVoter extends Voter
{
    /**
     * @var AccessDecisionManagerInterface
     */
    protected $decisionManager;

    /**
     * @var PermissionsHandlerInterface
     */
    protected $moduleHandler;

    public function __construct(AccessDecisionManagerInterface $accessDecisionManager, PermissionsHandlerInterface $permissionsHandler)
    {
        $this->decisionManager = $accessDecisionManager;
        $this->moduleHandler = $permissionsHandler;
    }

    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject)
    {
        // Check if attribute is declared as permission.
        if (!in_array($attribute, array_keys($this->moduleHandler->getPermissions()))) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        // Super Admin Pass
        if ($this->decisionManager->decide($token, [RoleInterface::ROLE_SUPER_ADMIN])) {
            return true;
        }

        // Deny access if user is not logged in.
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        return $user->hasPermission($attribute);
    }
}
