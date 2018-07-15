<?php

/*
 * This file is part of the EloyekunlePermissionsBundle package.
 *
 * (c) Elijah Oyekunle <https://elijahoyekunle.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloyekunle\PermissionsBundle\Tests;

use Eloyekunle\PermissionsBundle\DependencyInjection\Compiler\DoctrineMappingPass;
use Eloyekunle\PermissionsBundle\DependencyInjection\Compiler\ValidationPass;
use Eloyekunle\PermissionsBundle\EloyekunlePermissionsBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class BundleTest extends TestCase
{
    public function testBuildCompilerPasses()
    {
        $container = new ContainerBuilder();
        $bundle = new EloyekunlePermissionsBundle();
        $bundle->build($container);

        $config = $container->getCompilerPassConfig();
        $passes = $config->getBeforeOptimizationPasses();

        $foundMappingPass = false;
        $foundValidation = false;

        foreach ($passes as $pass) {
            if ($pass instanceof ValidationPass) {
                $foundValidation = true;
            } elseif ($pass instanceof DoctrineMappingPass) {
                $foundMappingPass = true;
            }
        }

        $this->assertTrue($foundMappingPass, 'DoctrineMappingPass was not found');
        $this->assertTrue($foundValidation, 'ValidationPass was not found');
    }
}
