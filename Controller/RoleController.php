<?php

namespace Eloyekunle\PermissionsBundle\Controller;

use Eloyekunle\PermissionsBundle\Doctrine\RoleManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    /** @var RoleManager */
    private $roleManager;

    public function __construct(RoleManager $roleManager)
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

    public function create(): Response
    {
    }
}
