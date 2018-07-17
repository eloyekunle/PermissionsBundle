<?php

/*
 * This file is part of the EloyekunlePermissionsBundle package.
 *
 * (c) Elijah Oyekunle <https://elijahoyekunle.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloyekunle\PermissionsBundle\Tests\DependencyInjection;

use Eloyekunle\PermissionsBundle\DependencyInjection\EloyekunlePermissionsExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;

/**
 * EloyekunlePermissionsExtension Test.
 *
 * @author Elijah Oyekunle <eloyekunle@gmail.com>
 */
class EloyekunlePermissionsExtensionTest extends TestCase
{
    /** @var ContainerBuilder */
    private $container;

    /** @var EloyekunlePermissionsExtension */
    private $extension;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->container = new ContainerBuilder();
        $this->extension = new EloyekunlePermissionsExtension();
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        unset($this->container, $this->extension);
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testLoadThrowsExceptionUnlessDatabaseDriverIsValid()
    {
        $config = $this->getEmptyConfig();
        $config['db_driver'] = 'bar';
        $this->extension->load([$config], $this->container);
    }

    public function testLoadManagerClassWithDefaults()
    {
        $this->createEmptyConfig();

        $this->assertParameter('orm', 'eloyekunle_permissions.storage');
        $this->assertAlias('eloyekunle_permissions.role_manager.default', 'eloyekunle_permissions.role_manager');
    }

    public function testModelClassWithDefaults()
    {
        $this->createEmptyConfig();

        $this->assertParameter('Acme\MyBundle\Document\Role', 'eloyekunle_permissions.model.role.class');
    }

    protected function createEmptyConfig()
    {
        $config = $this->getEmptyConfig();
        $this->extension->load(array($config), $this->container);
        $this->assertTrue($this->container instanceof ContainerBuilder);
    }

    protected function createFullConfig()
    {
        $config = $this->getFullConfig();
        $this->extension->load(array($config), $this->container);
        $this->assertTrue($this->container instanceof ContainerBuilder);
    }

    /**
     * getEmptyConfig.
     *
     * @return array
     */
    protected function getEmptyConfig()
    {
        $yaml = <<<EOF
db_driver: orm
role_class: Acme\MyBundle\Document\Role
EOF;
        $parser = new Parser();

        return $parser->parse($yaml);
    }

    /**
     * getFullConfig.
     *
     * @return array
     */
    protected function getFullConfig()
    {
        $yaml = <<<EOF
db_driver: orm
role_class: Acme\MyBundle\Document\Role
module:
    definitions_path: /var/www/config/modules
EOF;
        $parser = new Parser();

        return $parser->parse($yaml);
    }

    /**
     * @param string $value
     * @param string $key
     */
    private function assertAlias($value, $key)
    {
        $this->assertSame($value, (string) $this->container->getAlias($key), sprintf('%s alias is correct', $key));
    }

    /**
     * @param mixed  $value
     * @param string $key
     */
    private function assertParameter($value, $key)
    {
        $this->assertSame($value, $this->container->getParameter($key), sprintf('%s parameter is correct', $key));
    }

    /**
     * @param string $id
     */
    private function assertHasDefinition($id)
    {
        $this->assertTrue($this->container->hasDefinition($id) ?: $this->container->hasParameter($id));
    }

    /**
     * @param string $id
     */
    private function assertNotHasDefinition($id)
    {
        $this->assertTrue($this->container->hasDefinition($id) ?: $this->container->hasParameter($id));
    }
}
