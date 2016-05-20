<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\HttpKernel\Tests\DependencyInjection;

use Makhan\Component\HttpKernel\DependencyInjection\LazyLoadingFragmentHandler;
use Makhan\Component\HttpFoundation\Request;
use Makhan\Component\HttpFoundation\Response;

class LazyLoadingFragmentHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $renderer = $this->getMock('Makhan\Component\HttpKernel\Fragment\FragmentRendererInterface');
        $renderer->expects($this->once())->method('getName')->will($this->returnValue('foo'));
        $renderer->expects($this->any())->method('render')->will($this->returnValue(new Response()));

        $requestStack = $this->getMock('Makhan\Component\HttpFoundation\RequestStack');
        $requestStack->expects($this->any())->method('getCurrentRequest')->will($this->returnValue(Request::create('/')));

        $container = $this->getMock('Makhan\Component\DependencyInjection\ContainerInterface');
        $container->expects($this->once())->method('get')->will($this->returnValue($renderer));

        $handler = new LazyLoadingFragmentHandler($container, $requestStack, false);
        $handler->addRendererService('foo', 'foo');

        $handler->render('/foo', 'foo');

        // second call should not lazy-load anymore (see once() above on the get() method)
        $handler->render('/foo', 'foo');
    }
}
