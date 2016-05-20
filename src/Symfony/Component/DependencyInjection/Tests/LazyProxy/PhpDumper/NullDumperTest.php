<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\DependencyInjection\Tests\LazyProxy\PhpDumper;

use Makhan\Component\DependencyInjection\Definition;
use Makhan\Component\DependencyInjection\LazyProxy\PhpDumper\NullDumper;

/**
 * Tests for {@see \Makhan\Component\DependencyInjection\PhpDumper\NullDumper}.
 *
 * @author Marco Pivetta <ocramius@gmail.com>
 */
class NullDumperTest extends \PHPUnit_Framework_TestCase
{
    public function testNullDumper()
    {
        $dumper = new NullDumper();
        $definition = new Definition('stdClass');

        $this->assertFalse($dumper->isProxyCandidate($definition));
        $this->assertSame('', $dumper->getProxyFactoryCode($definition, 'foo'));
        $this->assertSame('', $dumper->getProxyCode($definition));
    }
}
