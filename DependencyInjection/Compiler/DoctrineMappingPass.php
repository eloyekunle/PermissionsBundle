<?php

/*
 * This file is part of the EloyekunlePermissionsBundle package.
 *
 * (c) Elijah Oyekunle <https://elijahoyekunle.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloyekunle\PermissionsBundle\DependencyInjection\Compiler;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DoctrineMappingPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $mappings = [
            realpath(__DIR__.'../../Resources/config/doctrine-mapping') => 'Eloyekunle\PermissionsBundle\Model',
        ];

        if (class_exists('Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass')) {
            $container->addCompilerPass(DoctrineOrmMappingsPass::createXmlMappingDriver($mappings, ['eloyekunle_permissions.model_manager_name'], 'eloyekunle_permissions.backend_type_orm'));
        }
    }
}
