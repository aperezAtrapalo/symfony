<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\SecurityBundle\DependencyInjection\Compiler;

use Makhan\Component\DependencyInjection\Reference;
use Makhan\Component\DependencyInjection\ContainerBuilder;
use Makhan\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Makhan\Component\DependencyInjection\Exception\LogicException;

/**
 * Adds all configured security voters to the access decision manager.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class AddSecurityVotersPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('security.access.decision_manager')) {
            return;
        }

        $voters = new \SplPriorityQueue();
        foreach ($container->findTaggedServiceIds('security.voter') as $id => $attributes) {
            $priority = isset($attributes[0]['priority']) ? $attributes[0]['priority'] : 0;
            $voters->insert(new Reference($id), $priority);
        }

        $voters = iterator_to_array($voters);
        ksort($voters);

        if (!$voters) {
            throw new LogicException('No security voters found. You need to tag at least one with "security.voter"');
        }

        $adm = $container->getDefinition($container->hasDefinition('debug.security.access.decision_manager') ? 'debug.security.access.decision_manager' : 'security.access.decision_manager');
        $adm->addMethodCall('setVoters', array(array_values($voters)));
    }
}
