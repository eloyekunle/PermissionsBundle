<?php

/*
 * This file is part of the EloyekunlePermissionsBundle package.
 *
 * (c) Elijah Oyekunle <https://elijahoyekunle.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\UserBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Registers the additional validators according to the storage.
 */
class ValidationPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasParameter('eloyekunle_permissions.storage')) {
            return;
        }

        $storage = $container->getParameter('eloyekunle_permissions.storage');

        if ('custom' === $storage) {
            return;
        }

        $validationFile = __DIR__.'/../../Resources/config/storage-validation/'.$storage.'.xml';

        $container->getDefinition('validator.builder')
            ->addMethodCall('addXmlMapping', array($validationFile));
    }
}
