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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class WBElasticaExtension
 *
 * @package WB\ElasticaBundle
 * @author  Ulrik Nielsen <un@wikibusiness.org>
 */
class WBElasticaExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($config, $configs);

        $container->setParameter('wb_elastica.options', $this->parseConfig($config));

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }

    /**
     * Needed to inject the kernel.debug value into the configuration.
     *
     * @param array            $config
     * @param ContainerBuilder $container
     *
     * @return Configuration
     */
    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        return new Configuration($container->getParameter('kernel.debug'));
    }

    /**
     * Parse the config array into a format Elastica can use.
     *
     * @param array $config
     *
     * @return array
     */
    protected function parseConfig(array $config)
    {
        $servers = $config['servers'];

        if (1 === count($servers)) {
            $server = array_pop($servers);
            unset($config['servers']);

            return array_merge($config, $server);
        }

        sort($config['servers']);

        return $config;
    }
}
