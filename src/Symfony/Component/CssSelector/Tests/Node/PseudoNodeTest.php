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
use Makhan\Component\CssSelector\Node\PseudoNode;

class PseudoNodeTest extends AbstractNodeTest
{
    public function getToStringConversionTestData()
    {
        return array(
            array(new PseudoNode(new ElementNode(), 'pseudo'), 'Pseudo[Element[*]:pseudo]'),
        );
    }

    public function getSpecificityValueTestData()
    {
        return array(
            array(new PseudoNode(new ElementNode(), 'pseudo'), 10),
        );
    }
}
