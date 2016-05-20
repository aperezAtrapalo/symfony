<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\DebugBundle\DependencyInjection\Compiler;

use Makhan\Bundle\WebProfilerBundle\EventListener\WebDebugToolbarListener;
use Makhan\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Makhan\Component\DependencyInjection\ContainerBuilder;

/**
 * Registers the file link format for the {@link \Makhan\Component\HttpKernel\DataCollector\DumpDataCollector}.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class DumpDataCollectorPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('data_collector.dump')) {
            return;
        }

        $definition = $container->getDefinition('data_collector.dump');

        if ($container->hasParameter('templating.helper.code.file_link_format')) {
            $definition->replaceArgument(1, $container->getParameter('templating.helper.code.file_link_format'));
        }

        if (!$container->hasParameter('web_profiler.debug_toolbar.mode') || WebDebugToolbarListener::DISABLED === $container->getParameter('web_profiler.debug_toolbar.mode')) {
            $definition->replaceArgument(3, null);
        }
    }
}
