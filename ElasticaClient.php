<?php
/*
 * This file is part of the WB\ElasticaBundle package.
 *
 * (c) Ulrik Nielsen un@wikibusiness.org
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
 */
class ElasticaClient extends Client
{
    /**
     * @param array           $options
     * @param LoggerInterface $logger
     */
    public function __construct(array $options, LoggerInterface $logger)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $this->options = $resolver->resolve($options);

        parent::__construct($this->options);
        $this->setLogger($logger);
    }

    /**
     * Specify default options.
     *
     * @param OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'roundRobin'      => false,
            'log'             => false,
            'retryOnConflict' => 0,
            'servers'         => []
        ]);
    }
}
