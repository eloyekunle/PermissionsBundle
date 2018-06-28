<?php


namespace Eloyekunle\PermissionsBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class EloyekunlePermissionsExtension extends Extension
{
    /**
     * @var array
     */
    private static $doctrineDrivers = array(
      'orm' => array(
        'registry' => 'doctrine',
        'tag' => 'doctrine.event_subscriber',
      ),
      'mongodb' => array(
        'registry' => 'doctrine_mongodb',
        'tag' => 'doctrine_mongodb.odm.event_subscriber',
      ),
      'couchdb' => array(
        'registry' => 'doctrine_couchdb',
        'tag' => 'doctrine_couchdb.event_subscriber',
        'listener_class' => 'FOS\UserBundle\Doctrine\CouchDB\UserListener',
      ),
    );

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader(
          $container,
          new FileLocator(__DIR__.'/../Resources/config')
        );

        if ('custom' !== $config['db_driver']) {
            if (isset(self::$doctrineDrivers[$config['db_driver']])) {
                $loader->load('doctrine.xml');
                $container->setAlias('eloyekunle_permissions.doctrine_registry', new Alias(self::$doctrineDrivers[$config['db_driver']]['registry'], false));
            } else {
                $loader->load(sprintf('%s.xml', $config['db_driver']));
            }
            $container->setParameter($this->getAlias().'.backend_type_'.$config['db_driver'], true);
        }

        if (isset(self::$doctrineDrivers[$config['db_driver']])) {
            $definition = $container->getDefinition('eloyekunle_permissions.object_manager');
            $definition->setFactory([new Reference('eloyekunle_permissions.doctrine_registry'), 'getManager']);
        }

        $this->remapParametersNamespaces($config, $container, array(
          '' => array(
            'db_driver' => 'eloyekunle_permissions.storage',
            'role_class' => 'eloyekunle_permissions.model.role.class'
          ),
        ));

        $this->loadRole($config, $container, $loader);
        $this->loadPermission($config, $container, $loader);

        if (!empty($config['module'])) {
            $this->loadModule($config['module'], $container, $loader);
        }
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     * @param array            $map
     */
    protected function remapParameters(array $config, ContainerBuilder $container, array $map)
    {
        foreach ($map as $name => $paramName) {
            if (array_key_exists($name, $config)) {
                $container->setParameter($paramName, $config[$name]);
            }
        }
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     * @param array            $namespaces
     */
    protected function remapParametersNamespaces(array $config, ContainerBuilder $container, array $namespaces)
    {
        foreach ($namespaces as $ns => $map) {
            if ($ns) {
                if (!array_key_exists($ns, $config)) {
                    continue;
                }
                $namespaceConfig = $config[$ns];
            } else {
                $namespaceConfig = $config;
            }
            if (is_array($map)) {
                $this->remapParameters($namespaceConfig, $container, $map);
            } else {
                foreach ($namespaceConfig as $name => $value) {
                    $container->setParameter(sprintf($map, $name), $value);
                }
            }
        }
    }

    private function loadRole(array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $loader->load('role.xml');
        $loader->load('doctrine_role.xml');

        $container->setAlias('eloyekunle_permissions.role_manager', new Alias('eloyekunle_permissions.role_manager.default', true));
    }

    private function loadPermission(array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $loader->load('permissions.xml');
    }

    private function loadModule(array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $loader->load('module.xml');

        $this->remapParametersNamespaces($config, $container, [
          '' => [
            'definitions_path' => 'eloyekunle_permissions.module.definitions_path'
          ]
        ]);
    }
}