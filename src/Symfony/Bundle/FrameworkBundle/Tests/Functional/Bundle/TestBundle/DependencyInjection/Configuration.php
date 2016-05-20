<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\Tests\Functional\Bundle\TestBundle\DependencyInjection;

use Makhan\Component\Config\Definition\Builder\TreeBuilder;
use Makhan\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    private $customConfig;

    public function __construct($customConfig = null)
    {
        $this->customConfig = $customConfig;
    }

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('test');

        if ($this->customConfig) {
            $this->customConfig->addConfiguration($rootNode);
        }

        return $treeBuilder;
    }
}
