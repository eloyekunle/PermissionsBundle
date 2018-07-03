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
use Eloyekunle\PermissionsBundle\Model\RoleInterface;
use Eloyekunle\PermissionsBundle\Model\RoleManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
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

            return new JsonResponse($role, Response::HTTP_CREATED);
        }

        return new JsonResponse(self::getErrorsFromForm($form), Response::HTTP_BAD_REQUEST);
    }

    public function edit(RoleInterface $role, Request $request): Response
    {
        $form = $this->createForm(RoleFormType::class, $role);
        $form->submit($request->request->all(), false);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->roleManager->updateRole($role);

            return new JsonResponse($role, Response::HTTP_OK);
        }

        return new JsonResponse(self::getErrorsFromForm($form), Response::HTTP_BAD_REQUEST);
    }

    private static function getErrorsFromForm(FormInterface $form)
    {
        $errors = [];
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = self::getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }

        return $errors;
    }
}
