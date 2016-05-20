<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Config\Tests\Definition\Builder;

use Makhan\Component\Config\Definition\Builder\NodeBuilder as BaseNodeBuilder;

class NodeBuilder extends BaseNodeBuilder
{
    public function barNode($name)
    {
        return $this->node($name, 'bar');
    }

    protected function getNodeClass($type)
    {
        switch ($type) {
            case 'variable':
                return __NAMESPACE__.'\\'.ucfirst($type).'NodeDefinition';
            case 'bar':
                return __NAMESPACE__.'\\'.ucfirst($type).'NodeDefinition';
            default:
                return parent::getNodeClass($type);
        }
    }
}
