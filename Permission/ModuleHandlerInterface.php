<?php


namespace Eloyekunle\PermissionsBundle\Permission;


interface ModuleHandlerInterface
{
    /**
     * Returns the list of currently active modules.
     *
     * @return array
     *   An associative array whose keys are the names of the modules and whose
     *   values are Extension objects.
     */
    public function getModuleList();
}