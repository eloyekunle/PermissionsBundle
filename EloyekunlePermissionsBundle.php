<?php

namespace Eloyekunle\PermissionsBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EloyekunlePermissionsBundle extends Bundle
{

    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $this->addRegisterMappingsPass($container);
    }

    /**
     * @param ContainerBuilder $container
     */
    private function addRegisterMappingsPass(ContainerBuilder $container)
    {
        $mappings = [
          realpath(
            __DIR__.'/Resources/config/doctrine-mapping'
          ) => 'Eloyekunle\PermissionsBundle\Model',
        ];

        if (class_exists(
          'Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass'
        )) {
            $container->addCompilerPass(
              DoctrineOrmMappingsPass::createXmlMappingDriver(
                $mappings,
                ['eloyekunle_permissions.model_manager_name'],
                'eloyekunle_permissions.backend_type_orm'
              )
            );
        }
    }
}