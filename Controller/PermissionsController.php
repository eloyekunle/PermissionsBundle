<?php

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

    public function getAll()
    {
        return new JsonResponse($this->moduleHandler->getModuleList());
    }
}
