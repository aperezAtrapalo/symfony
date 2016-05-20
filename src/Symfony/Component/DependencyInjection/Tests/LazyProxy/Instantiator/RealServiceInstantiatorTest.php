<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\DependencyInjection\Tests\LazyProxy\Instantiator;

use Makhan\Component\DependencyInjection\Definition;
use Makhan\Component\DependencyInjection\LazyProxy\Instantiator\RealServiceInstantiator;

/**
 * Tests for {@see \Makhan\Component\DependencyInjection\Instantiator\RealServiceInstantiator}.
 *
 * @author Marco Pivetta <ocramius@gmail.com>
 */
class RealServiceInstantiatorTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiateProxy()
    {
        $instantiator = new RealServiceInstantiator();
        $instance = new \stdClass();
        $container = $this->getMock('Makhan\Component\DependencyInjection\ContainerInterface');
        $callback = function () use ($instance) {
            return $instance;
        };

        $this->assertSame($instance, $instantiator->instantiateProxy($container, new Definition(), 'foo', $callback));
    }
}
