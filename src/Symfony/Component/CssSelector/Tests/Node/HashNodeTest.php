<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\CssSelector\Tests\Node;

use Makhan\Component\CssSelector\Node\HashNode;
use Makhan\Component\CssSelector\Node\ElementNode;

class HashNodeTest extends AbstractNodeTest
{
    public function getToStringConversionTestData()
    {
        return array(
            array(new HashNode(new ElementNode(), 'id'), 'Hash[Element[*]#id]'),
        );
    }

    public function getSpecificityValueTestData()
    {
        return array(
            array(new HashNode(new ElementNode(), 'id'), 100),
            array(new HashNode(new ElementNode(null, 'id'), 'class'), 101),
        );
    }
}
