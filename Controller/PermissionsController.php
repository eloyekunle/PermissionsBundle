<?php


namespace Eloyekunle\PermissionsBundle\Controller;


use Eloyekunle\PermissionsBundle\Permission\PermissionHandler;
use Eloyekunle\PermissionsBundle\Util\YamlDiscovery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class PermissionsController extends Controller
{

    protected $permissionHandler;

    public function __construct(PermissionHandler $permissionHandler)
    {
        $this->permissionHandler = $permissionHandler;
    }

    public function getAll()
    {
        //        $permissions = $this->permissionHandler->getPermissions();

        $permissions = YamlDiscovery::decode(
          '/home/elijah/www/html/infoworks/vasbackend/config/modules/reports.yaml'
        );

        return new JsonResponse($this->permissionHandler->getPermissions());

        //        return $this->render(
        //          '@EloyekunlePermissions/Permissions/list.html.twig',
        //          ['permissions' => $permissions]
        //        );
    }
}