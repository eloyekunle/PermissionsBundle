<?php


namespace Eloyekunle\PermissionsBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('eloyekunle_permissions');

        $supportedDrivers = array('orm', 'mongodb', 'couchdb', 'custom');

        $rootNode
          ->children()
              ->scalarNode('db_driver')
                  ->validate()
                      ->ifNotInArray($supportedDrivers)
                      ->thenInvalid('The driver %s is not supported. Please choose one of '.json_encode($supportedDrivers))
                  ->end()
                  ->cannotBeOverwritten()
                  ->isRequired()
                  ->cannotBeEmpty()
              ->end()
              ->scalarNode('role_class')->isRequired()->cannotBeEmpty()->end()
              ->scalarNode('model_manager_name')->defaultNull()->end()
          ->end();

        return $treeBuilder;
    }
}