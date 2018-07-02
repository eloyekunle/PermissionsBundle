<?php

namespace Eloyekunle\PermissionsBundle\Permission;

interface ModuleHandlerInterface
{
    /**
     * Returns the list of currently active modules with their permissions.
     *
     * @return array
     */
    public function getModuleList();

    public function getModuleNames();
}
