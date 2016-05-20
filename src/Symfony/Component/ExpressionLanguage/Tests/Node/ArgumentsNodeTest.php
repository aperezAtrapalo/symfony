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

use Makhan\Component\ExpressionLanguage\Node\ArgumentsNode;

class ArgumentsNodeTest extends ArrayNodeTest
{
    public function getCompileData()
    {
        return array(
            array('"a", "b"', $this->getArrayNode()),
        );
    }

    protected function createArrayNode()
    {
        return new ArgumentsNode();
    }
}
