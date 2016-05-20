<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\ExpressionLanguage\Tests\Node;

use Makhan\Component\ExpressionLanguage\Node\NameNode;

class NameNodeTest extends AbstractNodeTest
{
    public function getEvaluateData()
    {
        return array(
            array('bar', new NameNode('foo'), array('foo' => 'bar')),
        );
    }

    public function getCompileData()
    {
        return array(
            array('$foo', new NameNode('foo')),
        );
    }
}
