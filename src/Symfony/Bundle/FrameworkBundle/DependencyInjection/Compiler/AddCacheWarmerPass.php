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

use Makhan\Component\DependencyInjection\ContainerBuilder;
use Makhan\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Makhan\Component\DependencyInjection\Reference;

/**
 * Registers the cache warmers.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class AddCacheWarmerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('cache_warmer')) {
            return;
        }

        $warmers = array();
        foreach ($container->findTaggedServiceIds('kernel.cache_warmer') as $id => $attributes) {
            $priority = isset($attributes[0]['priority']) ? $attributes[0]['priority'] : 0;
            $warmers[$priority][] = new Reference($id);
        }

        if (empty($warmers)) {
            return;
        }

        // sort by priority and flatten
        krsort($warmers);
        $warmers = call_user_func_array('array_merge', $warmers);

        $container->getDefinition('cache_warmer')->replaceArgument(0, $warmers);
    }
}
