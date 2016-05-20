<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bridge\ProxyManager\Tests\LazyProxy\Instantiator;

use Makhan\Bridge\ProxyManager\LazyProxy\Instantiator\RuntimeInstantiator;
use Makhan\Component\DependencyInjection\Definition;

/**
 * Tests for {@see \Makhan\Bridge\ProxyManager\LazyProxy\Instantiator\RuntimeInstantiator}.
 *
 * @author Marco Pivetta <ocramius@gmail.com>
 */
class RuntimeInstantiatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RuntimeInstantiator
     */
    protected $instantiator;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->instantiator = new RuntimeInstantiator();
    }

    public function testInstantiateProxy()
    {
        $instance = new \stdClass();
        $container = $this->getMock('Makhan\Component\DependencyInjection\ContainerInterface');
        $definition = new Definition('stdClass');
        $instantiator = function () use ($instance) {
            return $instance;
        };

        /* @var $proxy \ProxyManager\Proxy\LazyLoadingInterface|\ProxyManager\Proxy\ValueHolderInterface */
        $proxy = $this->instantiator->instantiateProxy($container, $definition, 'foo', $instantiator);

        $this->assertInstanceOf('ProxyManager\Proxy\LazyLoadingInterface', $proxy);
        $this->assertInstanceOf('ProxyManager\Proxy\ValueHolderInterface', $proxy);
        $this->assertFalse($proxy->isProxyInitialized());

        $proxy->initializeProxy();

        $this->assertSame($instance, $proxy->getWrappedValueHolderValue());
    }
}
