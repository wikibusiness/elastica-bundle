<?php
/*
 * This file is part of the WB\ElasticaBundle package.
 *
 * (c) WikiBusiness <http://company.wikibusiness.org/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WB\ElasticaBundle;

use Elastica\Client;
use Elastica\Connection;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Psr\Log\LoggerInterface;

/**
 * Class ElasticaClient
 *
 * @package WB\ElasticaBundle
 * @author  Ulrik Nielsen <un@wikibusiness.org>
 */
class ElasticaClient extends Client
{
    /**
     * Constructor
     *
     * @param array           $options
     * @param LoggerInterface $logger
     */
    public function __construct(array $options, LoggerInterface $logger)
    {
        if (isset($options['servers'])) {
            $resolver = new OptionsResolver();
            $this->globalOptions($resolver);

            foreach ($options['servers'] as $index => $opts) {
                $options['servers'][$index] = $resolver->resolve($opts);
            }
        }

        $resolver = new OptionsResolver();
        $this->globalOptions($resolver);

        $options = $resolver->resolve($options);

        parent::__construct($options);
        $this->setLogger($logger);
    }

    /**
     * {@inheritdoc}
     */
    public function request($path, $method = Request::GET, $data = array(), array $query = array())
    {
        $start = microtime(true);
        $response = parent::request($path, $method, $data, $query);

        if (!defined('DEBUG') || (false === DEBUG)) {
            $response->setQueryTime(microtime(true) - $start);
        }

        return $response;
    }

    /**
     * Specify global default options.
     *
     * @param OptionsResolver $resolver
     */
    protected function globalOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'host'            => null,
            'log'             => false,
            'path'            => null,
            'persistent'      => true,
            'port'            => null,
            'proxy'           => null,
            'retryOnConflict' => 0,
            'roundRobin'      => false,
            'servers'         => [],
            'timeout'         => null,
            'transport'       => null,
            'url'             => null,
        ]);

        $resolver->setAllowedTypes('roundRobin', 'bool');
        $resolver->setAllowedTypes('url', ['null', 'string']);
        $this->setAllowedTypes($resolver);
    }

    /**
     * Specify server default options.
     *
     * @param OptionsResolver $resolver
     */
    protected function serverOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'host'       => null,
            'path'       => null,
            'persistent' => true,
            'port'       => null,
            'proxy'      => null,
            'timeout'    => null,
            'transport'  => null,
        ]);

        $this->setAllowedTypes($resolver);
    }

    /**
     * Set shared allowed server option types.
     *
     * @param OptionsResolver $resolver
     */
    protected function setAllowedTypes(OptionsResolver $resolver)
    {
        $resolver->setAllowedTypes('host', ['null', 'string']);
        $resolver->setAllowedTypes('path', ['null', 'string']);
        $resolver->setAllowedTypes('persistent', 'bool');
        $resolver->setAllowedTypes('port', ['null', 'int']);
        $resolver->setAllowedTypes('proxy', ['null', 'string']);
        $resolver->setAllowedTypes('timeout', ['null', 'int']);
        $resolver->setAllowedTypes('transport', ['null', 'string']);
    }
}
