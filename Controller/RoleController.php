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

use Eloyekunle\PermissionsBundle\Doctrine\RoleManager;
use Eloyekunle\PermissionsBundle\Form\Type\RoleFormType;
use Eloyekunle\PermissionsBundle\Model\RoleManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    /** @var RoleManager */
    private $roleManager;

    public function __construct(RoleManagerInterface $roleManager)
    {
        $this->roleManager = $roleManager;
    }

    public function getAll(): Response
    {
        return $this->render(
          '@EloyekunlePermissions/Role/list.html.twig',
          [
            'roles' => $this->roleManager->findRoles(),
          ]
        );
    }

    public function create(Request $request): Response
    {
        $role = $this->roleManager->createRole();
        $form = $this->createForm(RoleFormType::class, $role);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $this->roleManager->updateRole($role);

            return new JsonResponse($role);
        }
    }
}
