<?php

/*
 * This file is part of the EloyekunlePermissionsBundle package.
 *
 * (c) Elijah Oyekunle <https://elijahoyekunle.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloyekunle\PermissionsBundle\Tests\Functional;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Eloyekunle\PermissionsBundle\Doctrine\RoleManager;
use Eloyekunle\PermissionsBundle\EloyekunlePermissionsBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

class DependencyInjectionTest extends KernelTestCase
{
    public function testBundleLoaded()
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        $this->assertInstanceOf(RoleManager::class, $container->get('eloyekunle_permissions.role_manager'));
    }

    protected static function getKernelClass()
    {
        return TestKernel::class;
    }
}

class TestKernel extends Kernel
{
    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new DoctrineBundle(),
            new EloyekunlePermissionsBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(function (ContainerBuilder $container) {
            $container->loadFromExtension('framework', [
                'secret' => 'test',
                'router' => array(
                    'resource' => '%kernel.root_dir%/config/routing.yml',
                ),
            ]);
            $container->loadFromExtension('eloyekunle_permissions', [
                'db_driver' => 'orm',
                'role_class' => 'Eloyekunle\PermissionsBundle\Tests\TestRole',
            ]);
            $container->loadFromExtension('doctrine', [
                'dbal' => [
                    'driver' => 'pdo_sqlite',
                    'path' => $this->getCacheDir().'/testing.db',
                ],
                'orm' => [],
            ]);
        });
    }

    public function getCacheDir()
    {
        return sys_get_temp_dir().'/'.str_replace('\\', '-', get_class($this)).'/cache/'.$this->environment;
    }

    public function getLogDir()
    {
        return sys_get_temp_dir().'/'.str_replace('\\', '-', get_class($this)).'/log/'.$this->environment;
    }
}
