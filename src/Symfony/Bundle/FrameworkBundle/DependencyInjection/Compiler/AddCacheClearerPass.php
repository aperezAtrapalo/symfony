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
 * Registers the cache clearers.
 *
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class AddCacheClearerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('cache_clearer')) {
            return;
        }

        $clearers = array();
        foreach ($container->findTaggedServiceIds('kernel.cache_clearer') as $id => $attributes) {
            $clearers[] = new Reference($id);
        }

        $container->getDefinition('cache_clearer')->replaceArgument(0, $clearers);
    }
}
