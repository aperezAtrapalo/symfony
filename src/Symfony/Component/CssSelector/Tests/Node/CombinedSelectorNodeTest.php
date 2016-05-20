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

use Makhan\Component\CssSelector\Node\CombinedSelectorNode;
use Makhan\Component\CssSelector\Node\ElementNode;

class CombinedSelectorNodeTest extends AbstractNodeTest
{
    public function getToStringConversionTestData()
    {
        return array(
            array(new CombinedSelectorNode(new ElementNode(), '>', new ElementNode()), 'CombinedSelector[Element[*] > Element[*]]'),
            array(new CombinedSelectorNode(new ElementNode(), ' ', new ElementNode()), 'CombinedSelector[Element[*] <followed> Element[*]]'),
        );
    }

    public function getSpecificityValueTestData()
    {
        return array(
            array(new CombinedSelectorNode(new ElementNode(), '>', new ElementNode()), 0),
            array(new CombinedSelectorNode(new ElementNode(null, 'element'), '>', new ElementNode()), 1),
            array(new CombinedSelectorNode(new ElementNode(null, 'element'), '>', new ElementNode(null, 'element')), 2),
        );
    }
}
