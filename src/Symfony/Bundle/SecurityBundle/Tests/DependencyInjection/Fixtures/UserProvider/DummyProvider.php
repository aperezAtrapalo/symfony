<?php

namespace Makhan\Bundle\SecurityBundle\Tests\DependencyInjection\Fixtures\UserProvider;

use Makhan\Bundle\SecurityBundle\DependencyInjection\Security\UserProvider\UserProviderFactoryInterface;
use Makhan\Component\Config\Definition\Builder\NodeDefinition;
use Makhan\Component\DependencyInjection\ContainerBuilder;

class DummyProvider implements UserProviderFactoryInterface
{
    public function create(ContainerBuilder $container, $id, $config)
    {
    }

    public function getKey()
    {
        return 'foo';
    }

    public function addConfiguration(NodeDefinition $node)
    {
    }
}
