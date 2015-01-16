<?php

namespace WB\ElasticaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @var bool
     */
    private $debug;

    /**
     * @param bool $debug
     */
    public function  __construct($debug)
    {
        $this->debug = (Boolean) $debug;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('wb_elastica');

        $rootNode->children()
            ->scalarNode('roundRobin')->defaultFalse()->end()
            ->booleanNode('log')->defaultValue($this->debug)->end()
            ->scalarNode('retryOnConflict')->defaultValue(0)->end()
            ->arrayNode('servers')
                ->isRequired()
                ->requiresAtLeastOneElement()
                ->useAttributeAsKey('name', false)
                ->prototype('array')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('host')->defaultValue('127.0.0.1')->end()
                        ->scalarNode('port')->defaultValue(9200)->end()
                        ->scalarNode('path')->defaultNull()->end()
                        ->scalarNode('url')->defaultNull()->end()
                        ->scalarNode('proxy')->defaultNull()->end()
                        ->scalarNode('transport')->defaultNull()->end()
                        ->scalarNode('persistent')->defaultTrue()->end()
                        ->scalarNode('timeout')->defaultNull()->end()
                        ->scalarNode('proxy')->defaultNull()->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
