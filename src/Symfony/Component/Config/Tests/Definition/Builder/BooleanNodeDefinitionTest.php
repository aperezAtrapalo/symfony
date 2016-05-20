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

use Makhan\Component\Config\Definition\Builder\BooleanNodeDefinition;

class BooleanNodeDefinitionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Makhan\Component\Config\Definition\Exception\InvalidDefinitionException
     * @expectedExceptionMessage ->cannotBeEmpty() is not applicable to BooleanNodeDefinition.
     */
    public function testCannotBeEmptyThrowsAnException()
    {
        $def = new BooleanNodeDefinition('foo');
        $def->cannotBeEmpty();
    }
}
