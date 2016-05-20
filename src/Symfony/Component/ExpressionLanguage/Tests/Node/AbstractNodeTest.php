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

use Makhan\Component\ExpressionLanguage\Compiler;

abstract class AbstractNodeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getEvaluateData
     */
    public function testEvaluate($expected, $node, $variables = array(), $functions = array())
    {
        $this->assertSame($expected, $node->evaluate($functions, $variables));
    }

    abstract public function getEvaluateData();

    /**
     * @dataProvider getCompileData
     */
    public function testCompile($expected, $node, $functions = array())
    {
        $compiler = new Compiler($functions);
        $node->compile($compiler);
        $this->assertSame($expected, $compiler->getSource());
    }

    abstract public function getCompileData();
}
