<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\Tests\Functional\Bundle\TestBundle\DependencyInjection\Config;

class CustomConfig
{
    public function addConfiguration($rootNode)
    {
        $rootNode
            ->children()
                ->scalarNode('custom')->end()
            ->end()
        ;
    }
}
