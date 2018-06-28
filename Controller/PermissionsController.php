<?php


namespace Eloyekunle\PermissionsBundle\Controller;


use Eloyekunle\PermissionsBundle\Permission\PermissionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PermissionsController extends Controller
{

    protected $permissionHandler;

    public function __construct(PermissionHandler $permissionHandler)
    {
        $this->permissionHandler = $permissionHandler;
    }

    public function getAll()
    {
    }
}