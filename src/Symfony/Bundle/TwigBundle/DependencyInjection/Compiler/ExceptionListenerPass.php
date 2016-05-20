<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\TwigBundle\DependencyInjection\Compiler;

use Makhan\Component\DependencyInjection\ContainerBuilder;
use Makhan\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Registers the Twig exception listener if Twig is registered as a templating engine.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class ExceptionListenerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('twig')) {
            return;
        }

        // register the exception controller only if Twig is enabled
        if ($container->hasParameter('templating.engines')) {
            $engines = $container->getParameter('templating.engines');
            if (!in_array('twig', $engines)) {
                $container->removeDefinition('twig.exception_listener');
            }
        }
    }
}
