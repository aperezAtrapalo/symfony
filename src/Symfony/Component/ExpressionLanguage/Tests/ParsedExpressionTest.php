<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\ExpressionLanguage\Tests;

use Makhan\Component\ExpressionLanguage\Node\ConstantNode;
use Makhan\Component\ExpressionLanguage\ParsedExpression;

class ParsedExpressionTest extends \PHPUnit_Framework_TestCase
{
    public function testSerialization()
    {
        $expression = new ParsedExpression('25', new ConstantNode('25'));

        $serializedExpression = serialize($expression);
        $unserializedExpression = unserialize($serializedExpression);

        $this->assertEquals($expression, $unserializedExpression);
    }
}
