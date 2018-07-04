<?php

/*
 * This file is part of the EloyekunlePermissionsBundle package.
 *
 * (c) Elijah Oyekunle <https://elijahoyekunle.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloyekunle\PermissionsBundle\Controller;

use Eloyekunle\PermissionsBundle\Permission\ModuleHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class PermissionsController extends Controller
{
    protected $moduleHandler;

    public function __construct(ModuleHandlerInterface $moduleHandler)
    {
        $this->moduleHandler = $moduleHandler;
    }

    public function getPermissions()
    {
        return new JsonResponse($this->moduleHandler->getPermissions());
    }

    public function getModules()
    {
        return new JsonResponse($this->moduleHandler->getModuleList());
    }
}
