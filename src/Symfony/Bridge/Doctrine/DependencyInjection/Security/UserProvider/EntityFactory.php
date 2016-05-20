<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bridge\Doctrine\DependencyInjection\Security\UserProvider;

use Makhan\Component\Config\Definition\Builder\NodeDefinition;
use Makhan\Bundle\SecurityBundle\DependencyInjection\Security\UserProvider\UserProviderFactoryInterface;
use Makhan\Component\DependencyInjection\DefinitionDecorator;
use Makhan\Component\DependencyInjection\ContainerBuilder;

/**
 * EntityFactory creates services for Doctrine user provider.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 * @author Christophe Coevoet <stof@notk.org>
 */
class EntityFactory implements UserProviderFactoryInterface
{
    private $key;
    private $providerId;

    public function __construct($key, $providerId)
    {
        $this->key = $key;
        $this->providerId = $providerId;
    }

    public function create(ContainerBuilder $container, $id, $config)
    {
        $container
            ->setDefinition($id, new DefinitionDecorator($this->providerId))
            ->addArgument($config['class'])
            ->addArgument($config['property'])
            ->addArgument($config['manager_name'])
        ;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function addConfiguration(NodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('property')->defaultNull()->end()
                ->scalarNode('manager_name')->defaultNull()->end()
            ->end()
        ;
    }
}
