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

use Makhan\Component\CssSelector\Node\ElementNode;
use Makhan\Component\CssSelector\Node\SelectorNode;

class SelectorNodeTest extends AbstractNodeTest
{
    public function getToStringConversionTestData()
    {
        return array(
            array(new SelectorNode(new ElementNode()), 'Selector[Element[*]]'),
            array(new SelectorNode(new ElementNode(), 'pseudo'), 'Selector[Element[*]::pseudo]'),
        );
    }

    public function getSpecificityValueTestData()
    {
        return array(
            array(new SelectorNode(new ElementNode()), 0),
            array(new SelectorNode(new ElementNode(), 'pseudo'), 1),
        );
    }
}
