<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\DependencyInjection\Compiler;

use Makhan\Component\DependencyInjection\ContainerBuilder;
use Makhan\Component\DependencyInjection\Alias;

/**
 * Overwrites a service but keeps the overridden one.
 *
 * @author Christophe Coevoet <stof@notk.org>
 * @author Fabien Potencier <fabien@makhan.com>
 * @author Diego Saint Esteben <diego@saintesteben.me>
 */
class DecoratorServicePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definitions = new \SplPriorityQueue();
        $order = PHP_INT_MAX;

        foreach ($container->getDefinitions() as $id => $definition) {
            if (!$decorated = $definition->getDecoratedService()) {
                continue;
            }
            $definitions->insert(array($id, $definition), array($decorated[2], --$order));
        }

        foreach ($definitions as list($id, $definition)) {
            list($inner, $renamedId) = $definition->getDecoratedService();

            $definition->setDecoratedService(null);

            if (!$renamedId) {
                $renamedId = $id.'.inner';
            }

            // we create a new alias/service for the service we are replacing
            // to be able to reference it in the new one
            if ($container->hasAlias($inner)) {
                $alias = $container->getAlias($inner);
                $public = $alias->isPublic();
                $container->setAlias($renamedId, new Alias((string) $alias, false));
            } else {
                $definition = $container->getDefinition($inner);
                $public = $definition->isPublic();
                $definition->setPublic(false);
                $container->setDefinition($renamedId, $definition);
            }

            $container->setAlias($inner, new Alias($id, $public));
        }
    }
}
