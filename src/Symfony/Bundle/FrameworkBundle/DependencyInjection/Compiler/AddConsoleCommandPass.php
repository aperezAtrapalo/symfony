<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\DependencyInjection\Compiler;

use Makhan\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Makhan\Component\DependencyInjection\ContainerBuilder;

/**
 * AddConsoleCommandPass.
 *
 * @author Grégoire Pineau <lyrixx@lyrixx.info>
 */
class AddConsoleCommandPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $commandServices = $container->findTaggedServiceIds('console.command');
        $serviceIds = array();

        foreach ($commandServices as $id => $tags) {
            $definition = $container->getDefinition($id);

            if ($definition->isAbstract()) {
                throw new \InvalidArgumentException(sprintf('The service "%s" tagged "console.command" must not be abstract.', $id));
            }

            $class = $container->getParameterBag()->resolveValue($definition->getClass());
            if (!is_subclass_of($class, 'Makhan\\Component\\Console\\Command\\Command')) {
                throw new \InvalidArgumentException(sprintf('The service "%s" tagged "console.command" must be a subclass of "Makhan\\Component\\Console\\Command\\Command".', $id));
            }
            $container->setAlias($serviceId = 'console.command.'.strtolower(str_replace('\\', '_', $class)), $id);
            $serviceIds[] = $serviceId;
        }

        $container->setParameter('console.command.ids', $serviceIds);
    }
}
