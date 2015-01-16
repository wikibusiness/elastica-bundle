<?php
/*
 * This file is part of the WB\ElasticaBundle package.
 *
 * (c) WikiBusiness <http://company.wikibusiness.org/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WB\ElasticaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @package WB\ElasticaBundle
 * @author  Ulrik Nielsen <un@wikibusiness.org>
 */
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
            ->booleanNode('log')->defaultValue($this->debug)->end()
            ->scalarNode('retryOnConflict')->defaultValue(0)->end()
            ->scalarNode('roundRobin')->defaultFalse()->end()
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
