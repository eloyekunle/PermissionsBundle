<?php

/*
 * This file is part of the EloyekunlePermissionsBundle package.
 *
 * (c) Elijah Oyekunle <https://elijahoyekunle.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloyekunle\PermissionsBundle\Permission;

/**
 * Provides the available permissions based on yml files.
 *
 * To define permissions you can use a $module.permissions.yml file. This file
 * defines machine names, human-readable names, restrict access (if required
 * for
 * security warning), and optionally descriptions for each permission type. The
 * machine names are the canonical way to refer to permissions for access
 * checking.
 *
 * If your module needs to define dynamic permissions you can use the
 * permission_callbacks key to declare a callable that will return an array of
 * permissions, keyed by machine name. Each item in the array can contain the
 * same keys as an entry in $module.permissions.yml.
 *
 * Here is an example from the core filter module (comments have been added):
 *
 * @code
 * # The key is the permission machine name, and is required.
 * administer filters:
 *   # (required) Human readable name of the permission used in the UI.
 *   title: 'Administer text formats and filters'
 *   # (optional) Additional description fo the permission used in the UI.
 *   description: 'Define how text is handled by combining filters into text
 *   formats.'
 *   # (optional) Boolean, when set to true a warning about site security will
 *   # be displayed on the Permissions page. Defaults to false.
 *   restrict access: false
 *
 * # An array of callables used to generate dynamic permissions.
 * permission_callbacks:
 *   # Each item in the array should return an associative array with one or
 *   # more permissions following the same keys as the permission defined
 *   above.
 *   - Drupal\filter\FilterPermissions::permissions
 * @endcode
 */
class PermissionHandler implements PermissionHandlerInterface
{
    /**
     * The module handler.
     *
     * @var ModuleHandlerInterface
     */
    protected $moduleHandler;

    public function __construct(ModuleHandlerInterface $moduleHandler)
    {
        $this->moduleHandler = $moduleHandler;
    }

    /**
     * {@inheritdoc}
     */
    public function getPermissions()
    {
        $modules = $this->moduleHandler->getModuleList();
        $allPermissions = [];

        foreach ($modules as $moduleId => $module) {
            foreach ($module['permissions'] as $permissionId => $permission) {
                $permission['provider'] = $moduleId;
                $allPermissions[$permissionId] = $permission;
            }
        }

        return $this->sortPermissions($allPermissions);
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleNames()
    {
        $modules = [];
        foreach ($this->moduleHandler->getModuleList() as $moduleId => $module) {
            $modules[$moduleId] = $module['name'];
        }

        return $modules;
    }

    /**
     * Sorts the given permissions by provider name and title.
     *
     * @param array $all_permissions
     *                               The permissions to be sorted
     *
     * @return array[]
     *                 Each return permission is an array with the following keys:
     *                 - title: The title of the permission.
     *                 - description: The description of the permission, defaults to NULL.
     *                 - provider: The provider of the permission.
     */
    protected function sortPermissions(array $all_permissions = [])
    {
        $modules = $this->getModuleNames();

        uasort(
          $all_permissions,
          function (array $permission_a, array $permission_b) use ($modules) {
              if ($modules[$permission_a['provider']] == $modules[$permission_b['provider']]) {
                  return $permission_a['title'] > $permission_b['title'];
              }

              return $modules[$permission_a['provider']] > $modules[$permission_b['provider']];
          }
        );

        return $all_permissions;
    }
}
